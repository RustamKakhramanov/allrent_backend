<?php

use App\Models\User;
use App\Enums\TimeEnum;
use App\DTOs\SendSmsDTO;
use App\Models\Location\City;
use App\Models\Location\Place;
use Illuminate\Support\Carbon;
use App\Models\Company\Company;
use App\Models\Record\Schedule;
use App\Interfaces\SmsInterface;
use Illuminate\Support\Facades\Route;
use App\Enums\RolesAndPermissionsEnum;
use App\Transformers\CompanyTransformer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
