<?php

namespace App\Admin\Controllers;

use function trans;
use function config;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Admin\Models\Admin;
use App\Models\Company\Company;
use Encore\Admin\Auth\Database\Role;
use Encore\Admin\Controllers\UserController;

class AdminUserController extends UserController
{
    /**
     * {@inheritdoc}
     */
    protected function title () {
        return trans( 'admin.administrator' );
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid () {
        $userModel = config( 'admin.database.users_model' );

        $grid = new Grid( new $userModel() );

        $grid->model()->whereNot('id', auth()->user()->id);
        $grid->column( 'id', 'ID' )->sortable();
        $grid->column( 'username', trans( 'admin.username' ) );
        $grid->column( 'email', 'E-mail' );
        // $grid->column('phone', __('Телефон'))->display(function ($model) {
        //     return preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '+7 ($1) $2-$3', $model->phone). "\n";;
        // });
        $grid->column( 'name', trans( 'admin.name' ) );
        $grid->column( 'roles', trans( 'admin.roles' ) )->pluck( 'name' )->label();
        $grid->column( 'created_at', trans( 'admin.created_at' ) );
        $grid->column( 'updated_at', trans( 'admin.updated_at' ) );
        

        $grid->actions( function ( Grid\Displayers\Actions $actions ) {
            if ( $actions->getKey() == 1 ) {
                $actions->disableDelete();
            }
        } );

        $grid->tools( function ( Grid\Tools $tools ) {
            $tools->batch( function ( Grid\Tools\BatchActions $actions ) {
                $actions->disableDelete();
            } );
        } );

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail ( $id ) {
        $show = parent::detail( $id );
        $show->field( 'email', 'email' );
        $show->field('phone', __('Телефон'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form () {
        $form = parent::form();
        
        $form->email('email', 'E-mail')->required();
        if(auth()->user()->isAdministrator()){
            $form->multipleSelect('companies', 'Компания')->options(Company::all()->pluck('name', 'id'))->required();
        }
        // $form->mobile('phone', __('Телефон'))->options(['mask' => '+9 (999) 999-99-99']);
        
        return $form;
    }
}
