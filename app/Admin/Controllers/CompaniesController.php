<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Company\Company;
use Encore\Admin\Layout\Content;

class CompaniesController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '';


    public function index(Content $c){
        if(!user()?->can('*')){
            return redirect('/admin');
       }

        return parent::index($c);
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Company());
  
        $grid->column('id', __('Id'));

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
        $show = new Show(Company::findOrFail($id));

        $show->field('id', __('Id'));


        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Company());
        $form->display('id', 'Id');
        $form->text('name', 'Название');
        $form->text('slug', 'Урл компании (название в адресной строке)');
        $form->text('description', 'Краткое описание компании');

        $form->embeds('info', 'Дополнительная информация', function ($form) {
            $form->text('Специализация')->rules('required');
        });

        $form->saving(function (Form $form) {
            $form->slug =   $form->slug ? strtoslug($form->slug) : strtoslug($form->name);
        });

        return $form;
    }
}
