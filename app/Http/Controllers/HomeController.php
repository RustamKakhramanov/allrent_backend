<?php

namespace App\Http\Controllers;

use App\Enums\HomePageEnum;
use App\Transformers\PlaceTransformer;
use App\Repositories\Location\PlaceRepository;

class HomeController extends Controller
{
    public function index()
    {
        $case = HomePageEnum::Place;

        return fractal(
            PlaceRepository::first(),
            new PlaceTransformer
        )->parseIncludes($case->getDataIncludes());
    }
}
