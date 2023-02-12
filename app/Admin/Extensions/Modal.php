<?php

namespace App\Admin\Extensions;

use Closure;
use Illuminate\Contracts\Support\Renderable;

class Modal implements Renderable
{

    protected $view = '/admin/items/modal-column';


    public function __construct(protected $text,  protected $body, protected $header = '',)
    {
        foreach (get_object_vars($this) as $key => $value) {
            if ($value instanceof Closure) {
                $this->$key = $value();
            }
            if ($text instanceof Renderable) {
                $this->text  = $text->render();
            }
        }
    }

    public function render()
    {
        $vars = [
            'key' => uniqid(),
            'header' => $this->header,
            'text' => $this->text,
            'body' => $this->body
        ];

        return view($this->view, $vars)->render();
    }

    public static function column($text,  $body, $header = '')
    {
        $modal = new self($text,  $body, $header);

        return $modal;
    }
}
