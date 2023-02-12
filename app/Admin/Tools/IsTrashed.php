<?php

namespace App\Admin\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class IsTrashed extends AbstractTool
{
    protected function script()
    {
        $url = Request::fullUrlWithQuery(['is_trashed' => '_is_trashed_']);

        return <<<EOT

        $('input:radio.store-is_trashed').change(function () {

            var url = "$url".replace('_is_trashed_', $(this).val());

            $.pjax({container:'#pjax-container', url: url });

        });

        EOT;
    }

    public function render()
    {
        Admin::script($this->script());

        $options = [
            'both' => 'Все вакансии',
            'active' => 'Активные',
            'trashed' => 'Архивированые',
        ];

        return view('admin.tools.is_trashed', compact('options'));
    }
}
