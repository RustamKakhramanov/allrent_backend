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

class DuplicateAction extends RowAction
{
    public $name = 'Дублировать';

    public function handle(Model $model, Request $request)
    {

        $newVacancies = collect();
        collect($request->get('cities'))->each(
            function ($city_id) use ($model, $request, $newVacancies) {
                $new = $model->replicate();
                $new->city_id = $city_id;
                $new->creator_id = $request->get('user');
                $new->deleted_at = null;
                $new->is_hot = false;
                $new->save();
                $new->conditions()->saveMany($model->conditions);
                $new->tags()->saveMany($model->tags);

                $newVacancies->push($new);
            }
        );

        HistoryLogService::log(LogTypesEnum::CloneVacancy, $model, $newVacancies);

        if ($newVacancies->count() == 1) {
            $this->response()->redirect('/home')->success('Успешно дублировано');
        }

        return $this->response()->success('Успешно дублировано')->refresh();
    }

    // public function dialog()
    // {
    //     $this->confirm('Are you sure you want to restore?');
    // }

    public function form()
    {
        $this->select(
            'user',
            'Выберите ответственного'
        )->options(
            Admin::whereHas(
                'roles',
                fn ($q) => $q->whereIn('slug', AdminRolesEnum::getRecruitersRoles())
            )
                ->get()
                ->map(
                    function ($u) {
                        if ($u->id === auth()->user()->id) {
                            $u->name = 'Вы';
                        }

                        return $u;
                    }
                )
                ->pluck('name', 'id')
        );

        $this->multipleSelect(
            'cities',
            'Выбор городов в которых появится вакансия. Если вакансия одна, произойдет редирект на вакансию'
        )->options(City::where('id', '!=', Vacancy::withTrashed()->find($this->getKey())->city_id)->pluck('name', 'id'));
    }
}
