<?php

namespace App\Admin\Controllers;

use App\Models\City;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Admin\Forms\Tabs\{Country, Region, City as CityForm, Tags, Conditions};
use Encore\Admin\Layout\Content;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Widgets\Tab;

class VacnacyContentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'City';


    public function index(Content $content)
    {
        $forms = [
            'country' => Country::class,
            // 'region' => Region::class,
            'city' => CityForm::class,
            'tags' => Tags::class,
            'conditions' => Conditions::class,
        ];

        return $content
            ->title('Страны, Города, Tеги, Условия работы')
            ->body(Tab::forms($forms));
    }
}
