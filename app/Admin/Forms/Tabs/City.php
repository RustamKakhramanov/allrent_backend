<?php

namespace App\Admin\Forms\Tabs;

use App\Models\Region;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\City as Model;
use Encore\Admin\Widgets\Form;
use Illuminate\Support\Facades\DB;

class City extends Form
{
    public $title = 'Города';

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
            Model::query()->whereDoesntHave('vacancies', fn ($q) => $q->withTrashed())->delete();

            collect($request->input('cities'))
                ->filter(function ($value, $key) {
                    return $value['_remove_'] == 0;
                })->each(function ($item) {
                    Model::query()->firstOrCreate([
                        'name' => $item['name'],

                        // 'region_id' => $item['region_id']
                    ])
                        ->update(['country_id' => $item['country_id']]);
                });
        });

        admin_success('Успешно! Города с вакансиями удалиться не могут.');

        return back();
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->table('cities', 'Город', function ($form) {
            $form->text('name', 'Название')->rules('required');
            $form->select('country_id', 'Страна')->options(Country::all()->pluck('name', 'id'))->rules('required');
            // $form->select('region_id', 'Регион')->options(Region::all()->pluck('name', 'id'))->rules('required');
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
            'cities' => Model::all()->map(fn ($item) => [
                'name' => $item->name,
                'country_id' => $item->country_id,
                // 'region_id' => $item->region_id
            ])->toArray()
        ];
    }
}
