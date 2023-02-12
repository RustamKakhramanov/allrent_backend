<?php

namespace App\Admin\Extensions;

use Encore\Admin\Form\Field;
use Encore\Admin\Form\Field\MultipleSelect;

class Multiselect extends MultipleSelect
{
    protected $view = 'admin.form.multiselect';
 
}
