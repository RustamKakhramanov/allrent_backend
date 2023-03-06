<?php

namespace App\Admin\Forms\Tabs;

use Illuminate\Http\Request;
use App\Models\Location\Place as Model;
use Encore\Admin\Widgets\Form;
use Illuminate\Support\Facades\DB;



use App\Admin\Models\Admin;
use App\Models\Location\City;
use App\Models\Company\Company;
use Encore\Admin\Layout\Content;
use App\Services\Media\ImageCopyright;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class Place extends  Form
{
    public $title = 'Редактирование места';

    /**
     * Handle the form request.
     *
     * @param  Request $request
     *
     * @return  \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request)
    {
        DB::transaction(function () use ($request) {
            Model::query()->doesntHave('regions')->delete();
            // Model::query()->insert( );

            collect($request->input('countries'))
                ->filter(function ($value, $key) {
                    return $value['_remove_'] == 0;
                })->each(function ($item) {
                    Model::query()->firstOrCreate(['name' => $item['name']]);
                });
        });



        admin_success('Processed successfully.');

        return back();
    }

    /**
     * Build a form here.
     */
    public function form()
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
        });

        return $form;
    }

    /**
     * The data of the form.
     *
     * @return  array $data
     */
    public function data()
    {
        return [
            'countries' => [['name'=> 111]]
        ];
    }
}
