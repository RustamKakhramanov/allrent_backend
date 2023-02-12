<?php

namespace App\Admin\Forms\Tabs;

use App\Models\Tag;
use Illuminate\Http\Request;
use Encore\Admin\Widgets\Form;
use App\Models\Tag as Model;
use Illuminate\Support\Facades\DB;

class Tags extends Form
{

    /**
     * The form title.
     *
     * @var  string
     */
    public $title = 'Tеги';

    /**
     * Handle the form request.
     *
     * @param  Request $request
     *
     * @return  \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request)
    {

        DB::transaction(function () use ($request) {
            // Model::query()->insert( );
            Model::query()->doesntHave('vacancies')->delete();

            collect($request->input('tags'))->values()
                ->filter(function ($value, $key) {
                    return $value['_remove_'] == 0;
                })->each(function ($i) {
                    Model::query()->updateOrCreate([
                        'name' => $i['name'],
                        'slug' => $i['slug'] ? strtoslug($i['slug']) : strtoslug($i['name'])
                    ], ['is_main' => $i['is_main']]);
                });
        });






        admin_success('Processed successfully.');

        return back();
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->table('tags', function ($table) {
            $table->text('name', 'Название')->required();
            $table->text('slug', 'Слаг (на англ. не обязательно)');
            $table->switch('is_main', 'На главной');
        });

        //    $this->list('tags', 'Теги вводятся без #')->min(1);
    }

    public function data()
    {
        return [
            'tags' => Model::all()->map(fn ($item) => ['name' => $item->name, 'slug' => $item->slug, 'is_main' => $item->is_main])->toArray()
        ];
    }
}
