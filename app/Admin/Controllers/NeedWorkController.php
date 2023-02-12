<?php

namespace App\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Request;
use Illuminate\Support\Str;
use Encore\Admin\Widgets\Table;
use Encore\Admin\Controllers\AdminController;

class NeedWorkController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Отклики на вакансии';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    public function grid()
    {
        $grid = new Grid(new Request());
        $grid->model()->query()->where('vacancy_id', null);
        $grid->column('id', __('Id'));
   
        $grid->column('name', 'Имя');
        $grid->column('phone', 'Телефон');
        $grid->column('email', 'E-mail');
        $grid->column('comment', 'Комментарий')->display(function ($text) {
            return Str::limit(strip_tags($text), 50);
        })->modal('Текст', function ($model) {
            return $model->comment;
        });

        $grid->column('worksheet', 'Анкета')->display(function ($worksheet) {
            return empty($worksheet) ? 'Нет данных' : "Нажмите чтобы посмотреть";
        })->modal('Анкета', function ($model) {
            if (empty($model->worksheet)) {
                return 'Нет данных';
            }
            $tables = '';
            foreach ($model->worksheet as  $item) {
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


            return $tables;
        });

        $grid->column('created_at', __('Дата создания'));


        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableEdit();
        });

        return $grid;
    }
}
