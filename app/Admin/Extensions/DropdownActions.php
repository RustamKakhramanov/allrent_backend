<?php

namespace App\Admin\Extensions;
use App\Admin\Actions\Grid\Delete;
use Encore\Admin\Grid\Actions\Edit;
use Encore\Admin\Grid\Actions\Show;
use Encore\Admin\Grid\Displayers\DropdownActions as P;


class DropdownActions extends P
{

    protected $defaultClass = [Edit::class, Show::class, Delete::class];

}
