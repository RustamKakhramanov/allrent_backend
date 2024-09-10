<?php

use App\Models\Review;
use App\Models\Contact;
use App\Enums\CurrencyEnum;
use App\Enums\PriceTypeEnum;
use Illuminate\Http\Request;
use App\Models\Location\Place;
use Illuminate\Support\Facades\Route;
use App\Services\Media\ImageCopyright;
use App\Transformers\ImageTransformer;
use App\Transformers\PlaceTransformer;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AllowedController;
use App\Http\Controllers\Auth\SmsController;
use App\Http\Controllers\Rents\RentController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Companies\InviteController;
use App\Http\Controllers\Locations\PlacesController;
use App\Http\Controllers\Auth\RefreshTokenController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Companies\CompaniesController;
use App\Http\Controllers\Profile\ProfileRentsController;
use App\Http\Controllers\Companies\CompanySchedulesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['guest'])->group(function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', AuthenticationController::class);
        Route::post('register', RegistrationController::class);
        Route::post('refresh', RefreshTokenController::class);
        Route::post('forgot', [ForgotPasswordController::class, 'sendResetLinkEmail']);
        Route::post('reset', [ResetPasswordController::class, 'reset']);
        Route::get('verification/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');

        Route::post('/sms/login', [SmsController::class, 'login']);
        Route::post('/sms/register', [SmsController::class, 'register'])->middleware('cors');
        Route::post('/sms/confirm', AuthenticationController::class);
        Route::post('/sms/resend', [SmsController::class, 'resend']);
    });
});


Route::prefix('invite')->group(function () {
    Route::get('/{company}', [InviteController::class, 'getImage'])->withoutMiddleware('throttle');
});

// Route::group(['prefix' => 'password'], function () {
//     Route::get('reset/code', [PasswordController::class, 'getResetCode']);
//     Route::get('reset/code/verify', [PasswordController::class, 'verifyResetCode']);
//     Route::get('reset', [PasswordController::class, 'reset']);
//     Route::get('change', [PasswordController::class, 'change']);
// });


Route::apiResource('/companies', CompaniesController::class)->only(['index', 'show']);
Route::apiResource('/companies', CompaniesController::class)->middleware(['auth:api'])->except(['index', 'show']);

Route::group(['prefix' => 'companies/{company}'], function () {
    Route::apiResource('places', PlacesController::class);

    Route::middleware(['auth:api'])->group(function () {

        Route::group(['prefix' => 'places/{place}'], function () {
            Route::apiResource('schedules', CompanySchedulesController::class)->withoutMiddleware('auth:api');
            Route::apiResource('rents', RentController::class);
        });
    });

    Route::group(['prefix' => 'place'], function () {
    });
});

Route::group(['prefix' => 'profile', 'middleware' => 'auth:api'], function () {
    Route::get('/', [ProfileController::class, 'show']);
    Route::resource('/rents', ProfileRentsController::class);
});

Route::group(['prefix' => 'allowed'], function () {
    Route::get('/', [AllowedController::class, 'index']);
    Route::get('/time', [AllowedController::class, 'getTime']);
    Route::get('/schedule', [AllowedController::class, 'getSchedule']);
});

Route::get('/home', [HomeController::class, 'index']);

Route::post('/test', function(Request $request){
     $review = Place::first();
     $review->saveImage(new ImageCopyright($request->file('image')));
    // $review->setPhone('+77713602692', 'Владимир');
    // $review->setWhatsApp('+77713602692');
    // $review->setTelegram('@rustamKakhramanov');
    // $review->setInstagram('@kakhramanovRus');
    // $review->setMail('poshta@poshta.com');

    // return fractal($review, new PlaceTransformer)->parseIncludes('contacts');
});




//** TODO Make CHPU for companies and theirs places
//** TODO Make free chedules and mini hraphics */