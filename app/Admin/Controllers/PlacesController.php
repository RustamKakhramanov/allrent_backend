<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Admin\Forms\Cities;
use App\Admin\Models\Admin;
use App\Enums\CurrencyEnum;
use Illuminate\Support\Str;
use App\Enums\PriceTypeEnum;
use App\Models\Record\Price;
use App\Models\Location\City;
use Encore\Admin\Widgets\Tab;
use App\Models\Location\Place;
use App\Enums\ScheduleTypeEnum;
use App\Models\Company\Company;
use App\Models\Record\Schedule;
use Encore\Admin\Layout\Content;
use App\Services\Media\ImageCopyright;
use App\Admin\Forms\Tabs\Place as TabsPlace;
use Encore\Admin\Controllers\AdminController;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PlacesController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Place());
        if (!auth()->user()->isAdministrator()) {
            // $grid->model()->whereIn('company_id', User::find(auth()->user()->id)->companies()->pluck('companies.id'));
            $grid->model()->with('schedules');
            $grid->model()->orderBy('id');
            // $grid->actions(function ($actions) {
            //     $actions->disableView();
            //     $actions->disableEdit();
            // });

            $grid->disableCreateButton();
        }

        $grid->column('id', __('Id'));
        $grid->column('name', __('Название'));
        $grid->column('city.name', "Город")->style(';word-break:break-all;')->sortable();
        $grid->column('address', "Адрес");
        $grid->column('coordinates', "Координаты")->style(';word-break:break-all;')->display(function ($i) {
            $i = is_string($i) ? json_decode($i, true) : $i;

            return "Шир.{$i['latitude']} Долг.{$i['longitude']}";
        });

        $grid->column('description', __('Описание'))->display(function ($text) {
            return Str::limit(strip_tags($text), 50);
        })->modal('Текст', function ($model) {
            return $model->description;
        });

        $grid->column('schedules', __('График по умолчанию'))->display(function ($text) {

            $sch = collect($text)
                ->where('type',  ScheduleTypeEnum::Default())->first();

            if (isset($sch['schedule'])) {
                $start = $sch['schedule'][0];
                $end = $sch['schedule'][count($sch['schedule']) - 1];

                return  "$start - $end";
            }

            return 'Нет графика по умолчанию';
        });


        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Place::findOrFail($id));

        $show->field('id', __('Id'));


        return $show;
    }

    // public function edit($id, Content $content)
    // {
    //     $forms = [
    //         'basic'    => TabsPlace::class,
    //         'basic'    => TabsPlace::class,
    //     ];

    //     return $content
    //         ->title('Редактирование места')
    //         ->body(Tab::forms($forms));
    // }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Place());
        $form->display('id', 'Id');
        $form->text('name', 'Название');
        $form->text('slug', 'Cлаг для ЧПУ');

        $companies = auth()->user()->can('*') ? Company::all()->pluck('name', 'id') : Admin::find(auth()->user()->id)->companies->pluck('name', 'id');
        $form->select('city_id', 'Город')->options(City::all()->pluck('name', 'id'));
        $form->select('company_id', 'Компания')->options($companies)->default($companies->keys()->first());
        $form->text('address', 'Адрес');

        // draggable sorting since v1.6.12
        $form->textarea('description', 'Описание');

        $form->embeds('coordinates', 'Координаты',  function ($form) {

            $form->text('latitude', 'Широта')->rules('required');
            $form->text('longitude', 'Долгота')->rules('required');
        });


        $form->multipleFile('pictures', 'Картинки')->sortable()->removable();

        $form->divider('Настройка графиков');

        $form->morphMany('schedules', 'Графики', function (Form\NestedForm $form) {
            // $form->select('type', 'Тип')->options(['default' => 'По умолчанию'])->required();
            $form->display('id');
           
            $form->select('type', 'Тип')->options(['default' => 'По умолчанию', 'day' => 'Определенный день'])->default('default')->required();
            $form->date('date', 'Дата')->required()->default(now());
            $form->time('start_at', 'Начало');
            $form->time('end_at', 'Конец');
            $form->text('price_value', 'Прайс за час')->required();

            $form->hiddenArray('schedule', 'График');

            // $form->embeds('allPrices', 'Прайс на график', function ($form) {
            //     $form->select('type', 'Тип')->options(
            //         collect(PriceTypeEnum::cases())->mapWithKeys(fn ($i) => [$i->value => $i->getName()])->toArray()
            //     )->default(PriceTypeEnum::PerHour())->required();

            //     $form->date('start_date', 'Начало действия')->required()->default(now());
            //     $form->date('end_date', 'Конец действия')->required()->default(now()->addYears(3));

            //     $form->select('currency', 'Валюта')->options(
            //         collect(CurrencyEnum::cases())->mapWithKeys(fn ($i) => [$i->value => $i->getName()])->toArray()
            //     )->default(CurrencyEnum::KZT());

            //     $form->text('value', 'Значение')->required();
            // });

            $form->divider();
        });

        $form->saving(function (Form $form) {

            if (isset($form->_file_sort_['pictures'])) {
                Media::setNewOrder(
                    collect(explode(',', $form->_file_sort_['pictures']))->map(
                        fn ($i) => $form->model()->images[$i]->id
                    )->toArray()
                );
            }
            if (is_array($form->pictures)) {
                $form->model()->saveImages(collect($form->pictures)->map(fn ($i) => new ImageCopyright($i))->toArray());
                $form->model()->refresh();
            } elseif ($form->pictures === '_file_del_') {
                $form->model()->images[0]->delete();
            }

            if (is_array($form->schedules) && count($form->schedules)) {
                $form->schedules = collect($form->schedules)->map(function ($i) {
                    [$start, $end] = collect([$i['start_at'], $i['end_at']])->sort()->values()->toArray();



                    return [
                        "type" => $i['type'],
                        "date" => $i['date'],
                        "id" => $i['id'],
                        "_remove_" => $i['_remove_'],
                        "price_value" => $i['price_value'],
                        "schedule" =>  btime_intervals($start, $end, 'H:i', true)->toArray(),
                    ];
                })->toArray();
            }
        });


        // dd(Schedule::find(28)->allPrices);

        $form->tools(function (Form\Tools $tools) {

            // Disable `List` btn.
            $tools->disableList();

            // Disable `Delete` btn.
            $tools->disableDelete();

            $tools->add('<a  href="" class="btn btn-sm btn-success"><i class="fa fa-eye"></i>&nbsp;&nbsp;Редактировать график</a>');
        });


        $form->saved(function (Form $form) {

            collect($form->schedules)->where('_remove_', 0)->each(
                function ($i) use($form) {
                    $schedule = $form->model()->schedules()->where([
                        'date' => $i['date'],
                        'type' => $i['type'],
                    ])->first();
                    $schedule->allPrices()->delete();
                        
                    if (isset($i['price_value']) && $i['price_value']) {
                        $schedule->allPrices()->create([
                            'start_date' => now(),
                            'currency' => CurrencyEnum::KZT(),
                            'value' => (int) $i['price_value'],
                            'type' => PriceTypeEnum::PerHour(),
                        ]);
                    }

                }
            );
        });

        $form->footer(function ($footer) {
            $footer->disableViewCheck();


            // disable `Continue Creating` checkbox
            $footer->disableCreatingCheck();
        });


        return $form;
    }
}
