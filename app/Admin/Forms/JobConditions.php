<?php

namespace App\Admin\Forms;

use App\Models\City;
use App\Models\Region;
use App\Models\Country;
use App\Models\JobCondition;
use Illuminate\Http\Request;

use Encore\Admin\Widgets\StepForm;
use App\Models\JobConditionsCategory;
use App\Admin\Forms\Selectable\RegionForm;

class JobConditions extends StepForm
{
    public $title = 'Добавьте условия работы';

    public function handle(Request $request)
    {
        $data = collect($request->input('category'))->map(fn ($item) => ['name' => $item['name']])->values()->toArray();
        $error =  '';
        // dd($data);
        // if ($data['']['name']) {
        //     $country = Country::create($data['country']);
        // }
        collect($request->input('category'))->each(function ($category) {
            if ($category['_remove_'] == 0) {
                $category_model = JobConditionsCategory::query()->create(['name' => $category['name']]);

                if (isset($category['items']) && count($category['items']) >= 1) {
                    $category_model->conditions()->createMany(
                        collect($category['items'])->map(fn ($item) => ['name' => $item])
                    );
                }
            }
        });

        admin_success('Успешно');
    }

    protected function initFormAttributes()
    {
        $this->attributes = [
            'id'             => 'widget-form-' . uniqid(),
            'method'         => 'POST',
            'action'         => '',
            // 'class'          => 'form-horizontal',
            'accept-charset' => 'UTF-8',
            'pjax-container' => true,
        ];
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
        $category_exists = JobConditionsCategory::query()->exists();
        $condition_exists = JobCondition::query()->exists();

        $this->table('category', function ($table) {
            $table->text('name', 'Название')->required();
            $table->items('category_conditions', 'Условия работы')
                ->addVariables(
                    ['parent' => ['condition_category', 'Категория условий работы']]
                );
        });

        $this->html('<b>Вы можете добавлять новые новые условия и их характеристики, 
        а так же выбирать имеющиеся в выпадающем поле, если таковые существуют</b>');
    }
}
