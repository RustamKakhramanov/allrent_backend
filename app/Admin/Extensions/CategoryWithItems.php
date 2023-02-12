<?php

namespace App\Admin\Extensions;

use Encore\Admin\Form\Field;

class CategoryWithItems extends Field
{
    protected $view = 'admin.form.items';


    protected static $js = [
        '/js/admin/form/category-items.js',
    ];
}
