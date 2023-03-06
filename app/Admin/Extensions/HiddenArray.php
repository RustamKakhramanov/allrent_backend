<?php

namespace App\Admin\Extensions;

use Encore\Admin\Form\Field\Select;
use Encore\Admin\Form\Field\MultipleSelect;

class HiddenArray extends Select
{
    protected $view = 'admin.form.hidden';
 
}