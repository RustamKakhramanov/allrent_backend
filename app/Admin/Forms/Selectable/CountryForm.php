<?php

namespace App\Admin\Forms\Selectable;

use App\Models\Country;
use Encore\Admin\Grid\Filter;
use Encore\Admin\Grid\Selectable;

class CountryForm extends Selectable
{
    public $model = Country::class;

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