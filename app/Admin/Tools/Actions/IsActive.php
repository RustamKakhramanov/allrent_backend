<?php

namespace App\Admin\Tools\Actions;

use Encore\Admin\Admin;
use Encore\Admin\Actions\RowAction;

class IsActive extends RowAction
{

    public function __construct(protected int $id, protected bool $is_active, $labels)
    {
        $this->is_active = $is_active;
    }

    protected function script()
    {
        return <<<SCRIPT

        $('.row-item-active-btn').on('click', function() {
            $.ajax({
                method: 'post',
                url: '/admin/stores/release',
                data: {
                    _token:LA.token,
                    ids: [$(this).data('id')],
                    active: $(this).data('value')
                },
                success: function () {
                    $.pjax.reload('#pjax-container');
                    toastr.success('Успешно');
                }
            });
        });

        SCRIPT;
    }

    public function render()
    {
        Admin::script($this->script());

        $label = $this->is_active ? 'Деактивировать' : 'Активировать';
        $value = !$this->is_active;
        
        return "<a class='btn btn-xs row-item-active-btn' data-id='{$this->id}' data-value='{$value}'>{$label}</a>";
    }
}
