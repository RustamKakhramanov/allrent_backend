<?php

namespace App\Http\Controllers\Companies;

use PDO;
use App\DTOs\ImageDTO;
use App\Models\Company\Company;
use App\Http\Controllers\Controller;
use App\Transformers\ImageTransformer;

class InviteController extends Controller
{
    public function getImage(Company $company){
        return fractal(ImageDTO::make(['url' => url("/storage/images/first.jpg")]), new ImageTransformer);
    }
}
