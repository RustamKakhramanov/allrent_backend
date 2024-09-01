<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Grid;
use App\Models\Ability;
use Encore\Admin\Show;
use Encore\Admin\Form;

class AbilitiesController extends AdminController
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
        $grid = new Grid(new Ability());

        $grid->field('id', __('Id'));
        $grid->field('id', 'Id');
        $grid->field('icon', 'Иконка');
        $grid->field('name', 'Название');
        $grid->field('value', 'Значение');

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
        $show = new Show(Ability::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('id', 'Id');
        $show->field('icon', 'Иконка');
        $show->field('name', 'Название');
        $show->field('value', 'Значение');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Ability());
        $form->display('id', 'Id');
        $form->image('icon', 'Иконка');
        $form->text('name', 'Название');
        $form->text('value', 'Значение');

        return $form;
    }
}
