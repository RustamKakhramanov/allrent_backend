<?php

namespace App\Admin\Actions\Grid;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Encore\Admin\Grid\Actions\Delete as P;

class Delete extends P
{

    /**
     * @param Model $model
     *
     * @return Response
     */
    public function handle(Model $model)
    {
        $trans = [
            'failed'    => trans('admin.delete_failed'),
            'succeeded' => trans('admin.delete_succeeded'),
        ];

        try {
            DB::transaction(function () use ($model) {
                if (in_array($model::class, config('admin.database.force_deletes'))) {
                    $model->forceDelete();
                }
            });
            // forceDelete
        } catch (\Exception $exception) {
            return $this->response()->error("{$trans['failed']} : {$exception->getMessage()}");
        }

        return $this->response()->success($trans['succeeded'])->refresh();
    }
}
