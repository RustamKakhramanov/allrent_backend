<?php

namespace App\Admin\Forms\Tabs;

use App\Models\Country;
use Illuminate\Http\Request;
use Encore\Admin\Widgets\Form;
use App\Models\Region as Model;
use Illuminate\Support\Facades\DB;

class Region extends Form
{
    public $title = 'Регионы';

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
            Model::query()->doesntHave('cities')->delete();
            // Model::query()->insert( );

            collect($request->input('regions'))
                ->filter(function ($value, $key) {
                    return $value['_remove_'] == 0;
                })->each(function ($item) {
                    Model::query()->firstOrCreate(['name' => $item['name'], 'country_id' => $item['country_id']]);
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
        $this->table('regions', 'Регион', function ($form) {
            $form->text('name', 'Название')->rules('required');
            $form->select('country_id', 'Страна')->options(Country::all()->pluck('name', 'id'))->rules('required');
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
            'regions' => Model::all()->map(fn ($item) => ['name' => $item->name, 'country_id' => $item->country_id])->toArray()
        ];
    }
}
