<?php

namespace App\Admin\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class IsActive extends AbstractTool
{
    protected function script()
    {
        $url = Request::fullUrlWithQuery(['is_active' => '_is_active_']);

        return <<<EOT

        $('input:radio.store-is_active').change(function () {

            var url = "$url".replace('_is_active_', $(this).val());

            $.pjax({container:'#pjax-container', url: url });

        });

        EOT;
    }

    public function render()
    {
        Admin::script($this->script());

        $options = [
            true => 'Активен',
            false => 'Отключен',

        ];

        return view('admin.tools.is_active', compact('options'));
    }
}
