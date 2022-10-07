<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Company\Company;
use App\Models\Record\Rent;
use App\Models\Record\Schedule;
use App\Models\User;
use App\Policies\Company\CompanyPolicy;
use App\Policies\Rent\RentPolicy;
use App\Policies\Rent\SchedulePolicy;
use App\Policies\UserPolicy;
use Laravel\Passport\Passport;
use App\Services\Auth\Grants\{
    SmsGrant,
    GuestGrant,
    ImpersonateGrant,
};
use League\OAuth2\Server\AuthorizationServer;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Bridge\RefreshTokenRepository;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Rent::class => RentPolicy::class,
        Schedule::class => SchedulePolicy::class,
        Company::class => CompanyPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        app(AuthorizationServer::class)->enableGrantType(
            $this->makeSmsGrant(),
            Passport::tokensExpireIn()
        );
        app(AuthorizationServer::class)->enableGrantType(
            $this->makeGuestGrant(),
            Passport::tokensExpireIn()
        );


        app(AuthorizationServer::class)->enableGrantType(
            $this->makeImpersonateGrant(),
            Passport::tokensExpireIn()
        );

    }


    /**
     * @return SmsGrant
     * @throws BindingResolutionException
     */
    protected function makeSmsGrant()
    {
        $grant = new SmsGrant(
            $this->app->make(RefreshTokenRepository::class)
        );

        $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());

        return $grant;
    }

    /**
     * @return GuestGrant
     * @throws BindingResolutionException
     */
    protected function makeGuestGrant()
    {
        $grant = new GuestGrant(
            $this->app->make(RefreshTokenRepository::class)
        );

        $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());

        return $grant;
    }

    /**
     * @return ImpersonateGrant
     * @throws BindingResolutionException
     */
    protected function makeImpersonateGrant()
    {
        $grant = new ImpersonateGrant(
            $this->app->make(RefreshTokenRepository::class)
        );

        $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());

        return $grant;
    }
}
