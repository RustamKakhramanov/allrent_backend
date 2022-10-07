<?php


namespace App\Http\Controllers\Auth;


use App\Models\User;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use App\Http\Controllers\Controller;
use App\Transformers\SuccessTransformer;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;

/**
 * Class ResetPasswordController
 * @package App\Http\Controllers\Auth
 *
 * @OA\Post(
 *  path="/passport/reset",
 *  operationId="reset",
 *  tags={"Passport"},
 *  summary="Reset password",
 *  @OA\RequestBody(
 *   @OA\MediaType(
 *    mediaType="multipart/form-data",
 *    @OA\Schema(
 *     type="object",
 *     @OA\Property(property="token", type="string"),
 *     @OA\Property(property="email", type="string", format="email"),
 *     @OA\Property(property="password", type="string", format="password"),
 *     required={"token", "email", "password"}
 *    )
 *   )
 *  ),
 *  @OA\Response(
 *   response="200",
 *   description="OK",
 *   @OA\JsonContent(
 *    @OA\Property(property="data", type="object", ref="#/components/schemas/SuccessTransformer")
 *   )
 *  )
 * )
 */
class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected function sendResetResponse(Request $request, $response)
    {
        return fractal(true, new SuccessTransformer());
    }

    protected function sendResetFailedResponse(Request $request, $response)
    {
        return fractal(false, new SuccessTransformer());
    }

    protected function resetPassword(User $user, string $password)
    {
        $user->update([
            'password' => $password,
        ]);

        event(new PasswordReset($user));
    }

    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ];
    }

    protected function credentials(Request $request)
    {
        return $request->only(
            'email', 'password', 'token'
        );
    }
}
