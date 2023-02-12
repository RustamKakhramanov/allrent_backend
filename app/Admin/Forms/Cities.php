<?php

namespace App\Admin\Forms;

use App\Admin\Forms\Selectable\RegionForm;
use App\Models\City;
use App\Models\Region;
use App\Models\Country;
use Illuminate\Http\Request;

use Encore\Admin\Widgets\StepForm;

class Cities extends StepForm
{
    public $title = 'Добавьте города';

    public function handle(Request $request)
    {
        $data = $request->all();
        $error =  '';

        if ($data['country']['name']) {
            $country = Country::create($data['country']);
        }

        if ($data['region']['name']) {
            $region_data = $data['region'];
            if (isset($country) && !isset($region_data['country_id'])) {
                $region_data['country_id'] = $country->id;
            }

            if (!isset($country) && !isset($region_data['country_id'])) {
                $error .=  'Необходимо создать или указать страну; ';
            }

            $region = Region::create($region_data);
        }

        $city_data = $data['city'];

        if (isset($region) && !isset($city_data['region_id'])) {
            $city_data['region_id'] = $region->id;
        }

        if (!isset($region) && !isset($city_data['region_id'])) {
            $error .=  'Необходимо создать или указать Регион; ';
        }

        $city = City::query()->create($city_data);

        if ($error) {
            return $this->errorHandle($error);
        }


        if ($city) {
            admin_success('Успешно');
            return $this->next();
        } else {
            return $this->errorHandle();
        }
    }

    protected function errorHandle($error = 'Server Error')
    {
        admin_error($error);
        return $this->clear();
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $country_exists = Country::query()->exists();
        $region_exists = Region::query()->exists();

        $this->embeds('country', 'Страна', function ($form) use ($country_exists) {
            $country_exists ?  $form->text('name', 'Название') : $form->text('name', 'Название')->rules('required');
        });

        $this->embeds('region', 'Регион', function ($form) use ($country_exists, $region_exists) {
            if ($country_exists)
                $form->select('country_id', 'Выбирете страну для региона')->options(Country::all()->pluck('name', 'id'));


            $region_exists ?  $form->text('name', 'Название') : $form->text('name', 'Название')->rules('required');
        });

        $this->embeds('city', 'Город', function ($form) use ($region_exists) {
            if ($region_exists)
                $form->select('region_id', 'Выбирете Регион')->options(Region::all()->pluck('name', 'id'));


            $form->text('name', 'Название')->rules('required');
        });

        $this->html('<b>Вы можете добавлять новые новые регионы и города, 
        а так же выбирать имеющиеся в выпадающем поле, если таковые существуют</b>');
    }
}
