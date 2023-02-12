<?php

namespace App\Admin\Controllers;

use App\Models\Tag;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TagsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Tag';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Tag());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Название'));
        $grid->column('slug', __('Slug'));
        $grid->column('is_main', __('Виден под окном поиска'))->display(function ($i) {
            return $i ? 'Да' : "Нет";
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
        $show = new Show(Tag::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Название'));
        $show->field('slug', __('Slug'));
        $show->field('is_main', __('Виден под окном поиска'))->display(function ($i) {
            return $i ? 'Да' : "Нет";
        });
        $show->field('created_at', __('Создание'));
        $show->field('updated_at', __('Последнее обновление'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Tag());

        $form->text('name', __('Название'));
        $form->switch('is_main', __('Виден под окном поиска'));
        $form->saving(function (Form $form) {
            $form->slug = cyrillic_to_latin($form->name);
        });

        return $form;
    }
}
