<?php

namespace App\Admin\Tools\Actions;

use App\Models\Vacancy;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class RestoreAction extends RowAction
{
    public $name = 'Восстановить';

    public function handle (Vacancy $vacancy)
    {
        $vacancy->restore();

        return $this->response()->success('Recovered')->refresh();
    }

    public function dialog()
    {
        $this->confirm('Are you sure you want to restore?');
    }
}