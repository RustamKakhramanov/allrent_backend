<?php


namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Transformers\SuccessTransformer;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Spatie\Fractal\Fractal;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class VerificationController extends Controller
{
    use VerifiesEmails;

    /**
     * @return string
     */
    public function redirectTo()
    {
        return config('app.frontend_url');
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Redirector
     * @throws AuthorizationException
     */
    public function verify(Request $request)
    {
        $user = User::findOrFail($request->route('id'));

        if (!hash_equals((string)$request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        if ($user->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect($this->redirectTo());
    }

    /**
     * Resend the email verification notification.
     *
     * @param Request $request
     * @return Fractal
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            throw new BadRequestHttpException("User has already verified.");
        }

        $request->user()->sendEmailVerificationNotification();

        return fractal(true, new SuccessTransformer());
    }
}
