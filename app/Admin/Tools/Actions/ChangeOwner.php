<?php

namespace App\Admin\Tools\Actions;

use App\Models\City;
use App\Models\Vacancy;
use App\Admin\Models\Admin;
use App\Enums\AdminRolesEnum;
use Illuminate\Http\Request;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class ChangeOwner extends RowAction
{
    public $name = 'Сменить рекрутера';

    public function handle(Model $model, Request $request)
    {
        $model->update([
            'creator_id' => $request->get('creator_id')
        ]);
        return $this->response()->success('Смена рекрутера прошла успешно')->refresh();
    }

    // public function dialog()
    // {
    //     $this->confirm('Are you sure you want to restore?');
    // }

    public function form()
    {
        $this->select(
            'creator_id',
            'Выберите подходящего рекрутера'
        )->options(
            Admin::where('id', '!=', Vacancy::withTrashed()->find($this->getKey())->creator_id)
                ->whereHas(
                    'roles',
                    fn ($q) => $q->whereIn('slug', AdminRolesEnum::getRecruitersRoles())
                )
                ->pluck('name', 'id')
        );
    }
}
