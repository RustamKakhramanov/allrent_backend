<?php

namespace App\Providers;

use App\Interfaces\SmsInterface;
use App\Services\Notify\SmsService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            SmsInterface::class,
            function ($app, array $payload) {
                $client_class = config('services.sms.class');

                return new SmsService(
                    new $client_class(
                        config('services.sms.key'),
                        config('services.sms.url'),
                        config('services.sms.login'),
                        config('services.sms.password'),
                    )
                );
            }
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('timestamp', function ($attribute, $value, $parameters, $validator) {
            return is_timestamp($value);
        });

        Validator::extend('intorfloat', function($attribute, $value, $parameters, $validator) {
            return is_integer($value) || is_float($value) || is_double($value);
        });
    }
}
