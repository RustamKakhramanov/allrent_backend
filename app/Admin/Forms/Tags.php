<?php

namespace App\Admin\Forms;

use App\Models\Tag;
use Illuminate\Http\Request;
use Encore\Admin\Widgets\StepForm;

class Tags extends StepForm
{

    /**
     * The form title.
     *
     * @var  string
     */
    public $title = 'Добавьте теги';

    /**
     * Handle the form request.
     *
     * @param  Request $request
     *
     * @return  \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request)
    {

        $data = collect($request->input('tags'))->values()->map(
            fn ($i) => [
                'name' => $i['name'],
                'slug' => $i['slug'] ? cyrillic_to_latin($i['slug']) : cyrillic_to_latin($i['name'])
            ]
        )->toArray();

        Tag::query()->insert($data);

        return $this->next($request->all());
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->table('tags', function ($table) {
            $table->text('name', 'Название')->required();
            $table->text('slug', 'Слаг (на англ. не обязательно)');
        });

        //    $this->list('tags', 'Теги вводятся без #')->min(1);
    }

    protected function redirectToNextStep()
    {
        $index = array_search($this->current, $this->steps);
        $nextUrl = isset($this->steps[$index + 1]) ?

            $this->url . '?' . http_build_query(['step' => $this->steps[$index + 1]])
            :
            $this->url;

        return redirect($nextUrl);
    }
}
