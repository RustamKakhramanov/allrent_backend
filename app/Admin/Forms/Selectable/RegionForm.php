<?php

namespace App\Admin\Forms\Selectable;

use App\Models\Region;
use Encore\Admin\Grid\Filter;
use Encore\Admin\Grid\Selectable;

class RegionForm extends Selectable
{
    public $model = Region::class;

    public function make()
    {
        $this->column('id');
        $this->column('name');
        $this->column('created_at');

        $this->filter(function (Filter $filter) {
            $filter->like('name');
        });
    }
}