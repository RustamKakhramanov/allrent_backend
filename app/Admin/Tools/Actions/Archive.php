<?php

namespace App\Admin\Tools\Actions;


use App\Models\City;
use App\Models\Vacancy;
use App\Admin\Models\Admin;
use Illuminate\Http\Request;
use App\Enums\AdminRolesEnum;
use App\Enums\LogTypesEnum;
use App\Services\HistoryLogService;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class Archive  extends RowAction
{
    public $name = 'Архивировать';

    public function handle(Model $model, Request $request)
    {
        $model->delete();

        return $this->response()->success('Вакансия успешно архивирована')->refresh();
    }

    public function dialog()
    {
        $this->confirm('Подтвердите архивацию!');
    }
}
