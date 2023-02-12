<?php

namespace App\Admin\Tools\Actions\Multi;

use Encore\Admin\Grid\Tools\BatchAction;

class IsActive extends BatchAction
{
    protected $action;

    public function __construct($action = 1)
    {
        $this->action = $action;
    }

    public function script()
    {
        return <<<EOT
            $('{$this->getElementClass()}').on('click', function() {

                $.ajax({
                    method: 'post',
                    url: '{$this->resource}/release',
                    data: {
                        _token:LA.token,
                        ids: $.admin.grid.selects,
                        active: {$this->action}
                    },
                    success: function () {
                        $.pjax.reload('#pjax-container');
                        toastr.success('Успешно');
                    }
                });
            });
        EOT;

    }
}