<?php

namespace App\Admin\Controllers;

use App\Models\Tag;
use App\Models\City;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\History;
use App\Models\Vacancy;
use App\Admin\Forms\Tags;
use App\Admin\Forms\Cities;
use App\Admin\Models\Admin;
use App\Enums\CurrencyEnum;
use Illuminate\Support\Str;
use App\Enums\AdminRolesEnum;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Tab;
use App\Admin\Tools\IsTrashed;
use App\Admin\Extensions\Modal;
use Encore\Admin\Widgets\Table;
use Jxlwqq\DataTable\DataTable;
use Encore\Admin\Layout\Content;
use App\Enums\WorkExperienceEnum;
use App\Admin\Forms\JobConditions;
use App\Admin\Tools\Actions\Archive;
use App\Models\JobConditionsCategory;
use Encore\Admin\Widgets\MultipleSteps;
use App\Admin\Tools\Actions\ChangeOwner;
use App\Admin\Tools\Actions\BatchArchive;
use App\Admin\Tools\Actions\BatchRestore;
use App\Admin\Tools\Actions\RestoreAction;
use App\Admin\Tools\Actions\DuplicateAction;
use App\Enums\SalaryTypeEnum;
use Encore\Admin\Controllers\AdminController;
use App\Models\JobCondition as JobConditionModel;

class VacancyController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Вакансии';



    public function index(Content $content)
    {
        $steps = [];

        if (!City::query()->exists()) {
            $steps['cities'] = Cities::class;
        }


        if (!Tag::query()->exists()) {
            $steps['tags'] = Tags::class;
        }

        if (!JobConditionModel::query()->exists()) {
            $steps['conditions'] = JobConditions::class;
        }

        return $steps ? $content
            ->title('Настройка тегов')
            ->body(MultipleSteps::make($steps))
            :
            $content
            ->title($this->title())
            ->description($this->description['index'] ?? trans('admin.list'))
            ->body($this->grid());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Vacancy());

        switch (\request('is_trashed')) {
            case 'active':
                $grid->model();
                break;
            case 'trashed':
                $grid->model()
                    ->onlyTrashed();
                break;
            default:
                $grid->model()
                    ->withTrashed();
        }

        $grid->model()
            ->orderByDesc('deleted_at');
        $grid->model()->orderByDesc('id');

        $grid->column('id', __('ID'))->sortable();

        if (in_roles(
            [
                AdminRolesEnum::Administrator(),
                AdminRolesEnum::ManagerLogistic(),
                AdminRolesEnum::ManagerOffice(),
                AdminRolesEnum::ManagerSales()
            ]
        )) {
            if (
                in_roles([
                    AdminRolesEnum::ManagerLogistic(),
                    AdminRolesEnum::ManagerOffice(),
                    AdminRolesEnum::ManagerSales(),

                ]) && !AdminRolesEnum::Administrator()
            ) {
                $grid->model()->whereHas('conditions', function ($query) {
                    $query
                        ->whereHas('category', fn ($q) => $q->where('name', 'Направления'))
                        ->whereIn('name',  AdminRolesEnum::getDirectionsByRoles(user_roles()));
                });
            }

            $grid->column('creator_id', __('Ответственный'))->display(function ($id) {
                return $id === auth()->user()->id ? 'Вы' : Admin::find($id)->name ?? 'Не указан';
            })->modal('Текст', function ($model) {
                return $model->creator ?
                    new Table(
                        ['ID', 'Имя', 'email', 'username'],
                        [$model->creator->only(['id', 'name', 'email', 'username'])]
                    ) : null;
            })->style(';word-break:break-all;')
                ->sortable();
        } else {
            $grid->model()->where('creator_id', auth()->user()->id);
        }

        $grid->column('is_hot', 'Горячая')->style(';word-break:break-all;')->display(function ($i) {
            return $i ? 'Да' : "Нет";
        })->sortable();

        $grid->column('direction', "Направление")->style(';word-break:break-all;')->sortable()
            // ->filter(VacancyDirectionsEnum::getAllDirections())
        ;
        $grid->column('name', "Название");
        $grid->column('full_salary', 'Зарплпата');
   

        $grid->column('city.name', "Город")->style(';word-break:break-all;')->sortable();
        // $grid->column('description', __('Описание'))->display(function ($text) {
        //     return Str::limit(strip_tags($text), 50);
        // })->modal('Текст', function ($model) {
        //     return $model->description;
        // });

        // $grid->column('requirements', __('Требования'))->display(function ($text) {
        //     return Str::limit(strip_tags($text), 50);
        // })->modal('Текст', function ($model) {
        //     return $model->requirements;
        // });


        // $grid->column('responsibilities', __('Обязанности'))->display(function ($text) {
        //     return Str::limit(strip_tags($text), 50);
        // })->modal('Текст', function ($model) {
        //     return $model->responsibilities;
        // });


        $grid->column('conditions', __('Условия'))->display(function ($text) {
            return "Нажмите чтобы посмотреть";
        })->modal('Текст', function ($model) {
            $data =  $model->conditions->load('category')->groupBy('category.name')->toArray();
            $tables = '';
            foreach ($data as $key => $item) {
                $key = mb_strtoupper(mb_substr($key, 0, 1)) . mb_substr($key, 1);

                $tables .=  "<h4 style='padding-left:7px; font-weight:bold'>{$key}</h5>" .
                    (new Table(
                        [
                            "Название",
                            "Описание",
                        ],
                        collect($item)->map(function ($item) {
                            return collect($item)->only([
                                "name",
                                "description",
                            ])->toArray();
                        })->toArray()

                    ))->render();
            }

            return $tables;
        });



        $grid->column('deleted_at', __('Архивирована'))->display(fn ($d) => $d ?? 'Нет');
        $grid->column('requests_count', "Откликов")->display(fn ($d) => $d ?? 'Нет');
        $grid->column('shows_count', "Просмтров")->display(fn ($d) => $d ?? 'Нет');
        $grid->column('created_at', __('Создание'));
        $grid->column('updated_at', __('Последнее обновление'));

        // $grid->filter(function ($filter) {
        //     $filter->scope('trashed', 'Архивированные')->onlyTrashed();
        // });

        $grid->actions(function ($actions) {
            $actions->add(new ChangeOwner());

            if ($actions->row->deleted_at) {
                $actions->add(new RestoreAction());
            } else {
                $actions->add(new DuplicateAction());
                $actions->add(new Archive());
            }
        });

        $grid->batchActions(function ($batch) {
            if (\request('is_trashed') == 'trashed') {
                $batch->add(new BatchRestore());
            }
            if (\request('is_trashed') !== 'trashed') {
                $batch->add(new BatchArchive());
            }
        });


        $grid->tools(function ($tools) {
            $tools->append(new IsTrashed());
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });

        // $grid = $grid->actions(function ( $actions) {
        //     $actions->disableView();
        //     $actions->add(new ActiveButton($actions->row->id, $actions->row->is_hot));
        // });

        // $grid->tools(function ($tools) {
        //     $tools->append(new IsActive());
        //     $tools->batch(function ($batch) {
        //         $batch->add('Активировать', new ButchActiveButton(1));
        //         $batch->add('Деактивировать', new ButchActiveButton(0));
        //     });

        return $grid->paginate(20);
    }

    /**
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    protected function detail(Vacancy $vacancy)
    {
        $show = new Show($vacancy);
        if (in_roles(
            [
                AdminRolesEnum::Administrator(),
                AdminRolesEnum::ManagerLogistic(),
                AdminRolesEnum::ManagerOffice(),
                AdminRolesEnum::ManagerSales(),

            ]
        )) {
            $show->creator_id(__('Ответственный'))->as(
                fn ($id) => Modal::column(
                    Admin::find($id)->name,
                    new Table(
                        ['ID', 'Имя', 'email', 'username'],
                        [$vacancy->creator->only(['id', 'name', 'email', 'username'])]
                    )
                )
            )->unescape();
        }


        $show->divider();

        $show->field('requests_count', "Количество откликов");
        $show->field('shows_count', "Количество просмтров");

        $show->field('direction', "Направление");
        $show->field('direction', "Направление");
        $show->field('name', "Название");
        $show->field('full_salary', 'Зарплпата');
        $show->field('salary_type', 'Тип зарплаты');
        $show->field('city.name', "Город");
        $show->field('description', __('Описание'))->unescape();
        $show->field('requirements', __('Требования'))->unescape();
        $show->responsibilities(__('Обязанности'))->unescape();


        $show->conditions('Условия')->as(fn () => $vacancy->conditions->pluck('name')->implode(', '));

        $show->deleted_at(__('Архивирована'))->as(fn ($at) => $at ? 'Да' : 'Нет');
        $show->field('created_at', __('Создание'));
        $show->field('updated_at', __('Последнее обновление'));

        // $grid->filter(function ($filter) {
        //     $filter
        return $show->render();
    }


    private function getVacancyRequests(Vacancy $vacancy)
    {
        $headers = ['Id', 'Имя', 'Телефон', 'E-mail', 'Комментарий', 'Анкета', 'Дата'];

        $rows = $vacancy->requests->map(function ($r) {
            $r->comment = Modal::column(
                Str::limit(strip_tags($r->comment), 50),
                $r->comment
            )->render();

            $getWorksheet = function ($worksheet) {
                if (empty($worksheet)) {
                    return 'Нет данных';
                }

                $tables = '';
                foreach ($worksheet as  $item) {
                    $data = collect($item['data']);
                    // $keys = $data->pluck('title')->toArray();

                    $items = $data->map(function ($item) {
                        return [
                            $item['title'],
                            count($item['data']) > 1 ?  implode('<br/>', $item['data']) : $item['data'][0]
                        ];
                    })->toArray();

                    $tables .=  "<h4 style='padding-left:7px; font-weight:bold'>{$item['title']}</h5>" .
                        (new Table(
                            [
                                'Описание',
                                'Значения',
                            ],

                            $items

                        ))->render();
                }


                return Modal::column(
                    'Нажмите чтобы посмотреть',
                    $tables
                )->render();
            };

            $r->worksheet = $getWorksheet($r->worksheet);

            return $r->only('id', 'name', 'phone', 'email', 'comment', 'worksheet', 'created_at');
        })->toArray();

        $table = new DataTable($headers, $rows);


        return  $table->render();
    }
    // {
    //     $headers = ['Id', 'Email'];

    //     return (new Table($headers, $vacancy->requests->map(fn ($i) => $i->only('id', 'email'))->toArray()))->render();
    // }


    private function getVacancyHistory(Vacancy $vacancy)
    {

        $headers = ['Тип события', 'Сообщение', 'Дата'];
        $rows = $vacancy->logs->map(function (History $i) {
            $i->data = $i->data['data'];
            return $i->only('type', 'data', 'created_at');
        })->toArray();


        $table = new DataTable($headers, $rows);

        return  $table->render();
    }




    public function show($id, Content $content)
    {

        $vacancy = Vacancy::withTrashed()->findOrFail($id);

        $tab = new Tab();
        $tab->add('Описание', $this->detail($vacancy));
        $tab->add('Отклики', $this->getVacancyRequests($vacancy));
        $tab->add('История', $this->getVacancyHistory($vacancy));


        return $content
            // ->title('Страны, Города, Tеги, Условия работы')
            ->body($tab);
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Vacancy());
        $form->column(1.5/2, function ($form) {
        
        $form->display('id', __('ID'));
        $form->hidden('creator_id')->default(auth()->user()->id);
        $form->multipleSelect('tags', 'Теги')->options(Tag::all()->pluck('name', 'id'));
        // $form->select('direction', 'Выберите направление')->options(VacancyDirectionsEnum::getAllDirections())->required();
        $form->select('city_id', 'Выберите город')->options(City::all()->pluck('name', 'id'))->required();
        // $form->select('education', 'Укажите образование')->options(
        //     EducationEnum::toCollectCases()->mapWithKeys(fn ($i, $k) => [$i => $i])
        // )->required();

        $form->select('experience', 'Укажите опыт работы')->options(
            WorkExperienceEnum::toCollectCases()->mapWithKeys(fn ($i, $k) => [$i => $i])
        )->required();
        $form->text('name', 'Введите название')->required();

        $form->divider('Введите заработную плату')->style('text-align', 'left');
        $form->currency('salary_start', 'От')->required()->symbol('');
        $form->currency('salary_end', 'До')->required()->symbol('');

        $form->select('salary_currency', 'Валюта')->options(
            CurrencyEnum::toCollectCases()->mapWithKeys(fn ($i, $k) => [$i => $i])
        )->required();

        $form->select('salary_type', 'Тип зарплаты')->options(
            SalaryTypeEnum::toCollectCases()->mapWithKeys(fn ($i, $k) => [$i => $i])
        )->required();

        $form->divider();

 

        $form->ckeditor('description', 'Введите описание');
        $form->ckeditor('responsibilities', 'Введите обязанности');
        $form->ckeditor('requirements', 'Введите требования');
        $form->switch('is_hot', 'Горячая');

        JobConditionsCategory::query()->whereHas('conditions')->with('conditions')->each(function ($item, $i) use ($form) {
            $form->checkbox("conditions", $item->name)->options(function () use ($item) {
                return $item->conditions->pluck('name', 'id')->toArray();
            });
        });
    });
        // $form->saving(function (Form $form) {
        //     $form->conditions = collect($form->conditions)->filter()->toArray();
        //     dd($form->roles, $form->name);
        // });

        return $form;
    }

    public function destroy($id)
    {
        return $this->form()->forceDelete($id);
    }
}
