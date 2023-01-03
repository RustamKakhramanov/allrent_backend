<?php

namespace App\Http\Controllers;

use App\DTOs\HomeDTO;
use App\Enums\HomePageEnum;
use App\Repositories\HomeRepository;
use App\Transformers\HomeTransformer;
use App\Transformers\PlaceTransformer;
use App\Repositories\Location\PlaceRepository;

class HomeController extends Controller
{
    public function index()
    {
        $case = HomePageEnum::Place;

        return fractal(
            HomeRepository::parse($case),
            new HomeTransformer
        )->parseIncludes($case->getDataIncludes());
    }
}
