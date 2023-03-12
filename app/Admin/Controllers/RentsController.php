<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\Record\Rent;
use App\Models\Location\Place;
use Encore\Admin\Controllers\AdminController;

class RentsController extends AdminController
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
        $grid = new Grid(new Rent());
        $grid->model()
        // ->where('rentable_type',)
        ->whereIn(
            'rentable_id', 
            Place::query()->whereIn('company_id', User::find(auth()->user()->id)->companies()->pluck('companies.id'))->pluck('id')->toArray()
        )->orderByDesc('id');

        $grid->column('id', __('Id'));

        $grid->column('user_id', __('Арендатор'))->display(function ($id) {
            $user = User::find($id);
            return  "$user->name ($user->phone)";
        });

        $grid->column('scheduled_at', __('Начало аренды'))->display(function ($d) {
            return $d ? cparse($d)->timezone('Asia/Almaty')->toDateTimeString() : 'Не установлено';
        })->filter('date');

        $grid->column('scheduled_end_at', __('Конец аренды'))->display(function ($d) {
            return $d ? cparse($d)->timezone('Asia/Almaty')->toDateTimeString() : 'Не установлено';

        })->filter('date');

        $grid->column('start_at', __('Фактическое начало аренды'))->display(function ($d) {
            return $d ? cparse($d)->timezone('Asia/Almaty')->toDateTimeString() : 'Не установлено';

        });
        $grid->column('end_at', __('Фактический конец аренды'))->display(function ($d) {
            return $d ? cparse($d)->timezone('Asia/Almaty')->toDateTimeString() : 'Не установлено';

        });

        $grid->column('amount', __('Nbg'));
        $grid->column('currency', __('Nbg'));
        $grid->column('is_paid', __('Nbg'));

        
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
        $show = new Show(Rent::findOrFail($id));

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
        $form = new Form(new Rent());
        $form->display('id', 'Id');
        $form->text('', '');
     
        return $form;
    }

}
