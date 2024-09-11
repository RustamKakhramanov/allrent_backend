<?php


namespace App\Http\Controllers\Auth;


use Throwable;
use App\Models\User;
use Illuminate\Support\Carbon;
use App\Interfaces\SmsInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use App\Transformers\SuccessTransformer;
use App\Http\Requests\ConfirmCodeRequest;
use App\Http\Requests\SmsRegistrationRequest;
use App\Http\Requests\SmsAuthenticationRequest;
use App\Services\SmsService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class SmsController extends Controller
{
    public function __construct(protected SmsInterface $service) {}

    protected function getUser($phone): User
    {
        return User::findByPhone($phone) ?:  throw new NotFoundHttpException();
    }

    public function login(SmsAuthenticationRequest $request)
    {
        $this->sendCode($this->getUser($request['phone']));

        return fractal(true, new SuccessTransformer());
    }

    public function register(SmsRegistrationRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = User::create(array_merge($request->validated(), ['email_verified_at' => Carbon::now()]));

            event(new Registered($user));

            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();

            throw new $exception;
        }

        $this->sendCode($user);

        return fractal(true, new SuccessTransformer());
    }


    public function resend(SmsAuthenticationRequest $request)
    {
        return $this->login($request);
    }

    protected function sendCode(User $user)
    {
        $code = $user->generateAuthCode();

        if (App::isProduction()) {
            $this->service->send($user->phone, "Record Ваш проверочный код: {$code}");
        }
    }
}
