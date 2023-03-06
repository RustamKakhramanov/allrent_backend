<?php

namespace App\Admin\Forms\Tabs;

use Encore\Admin\Widgets\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Schedule extends  Form
{
    public $title = 'Редактирование графиков работы';

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
            Model::query()->doesntHave('regions')->delete();
            // Model::query()->insert( );

            collect($request->input('countries'))
                ->filter(function ($value, $key) {
                    return $value['_remove_'] == 0;
                })->each(function ($item) {
                    Model::query()->firstOrCreate(['name' => $item['name']]);
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
        $this->table('countries', 'Страна', function ($form) {
            $form->text('name', 'Название')->rules('required');
        });
    }

    /**
     * The data of the form.
     *
     * @return  array $data
     */
    public function data()
    {
        return [
            'countries' => [['name'=> 111]]
        ];
    }
}