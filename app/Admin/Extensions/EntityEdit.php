<?php

namespace App\Admin\Extensions;

use Encore\Admin\Actions\GridAction;

class EntityEdit extends GridAction
{

/*    public function render()
    {
        return "<a class='btn btn-xs btn-success fa fa-check grid-check-row' data-id='{$this->id}'></a>";
    }*/

    public function html()
    {
        return "<a class='btn btn-xs btn-success fa fa-check grid-check-row' data-id='{$this->id}'></a>";
    }
}
