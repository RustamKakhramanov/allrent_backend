<?php

namespace App\Admin\Tools\Actions;

use Illuminate\Support\Collection;
use Encore\Admin\Actions\BatchAction;

class BatchRestore extends BatchAction
{
    public $name = 'Пакетное восстановление';

    public function handle (Collection $collection)
    {
        $collection->each->restore();

        return $this->response()->success('Вакансии восстановлены')->refresh();
    }

    public function dialog ()
    {
        $this->confirm('Подтвердите действие!');
    }
}