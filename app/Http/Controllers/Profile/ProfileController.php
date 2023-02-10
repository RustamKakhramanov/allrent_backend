<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Transformers\UserTransformer;

class ProfileController extends Controller
{

    public function show()
    {
        return fractal(user(), new UserTransformer);
    }
}
