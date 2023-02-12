<?php

namespace App\Admin\Tools\Actions;

use Encore\Admin\Actions\BatchAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class BatchArchive extends BatchAction
{
    protected $selector = '.archive-posts';

    

    public function handle(Collection $collection, Request $request)
    {
        foreach ($collection as $model) {
            $model->delete();
        }

        return $this->response()->success('Вакансии архивированы!')->refresh();
    }

    public function form()
    {
        $this->text('reason', 'Причина архивации?');
    }



    public function html()
    {
        return "<a class='archive-posts btn btn-sm btn-default'><i class='fa fa-info-circle'></i>Архивировать</a>";
    }
}
