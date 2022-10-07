<?php


namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Transformers\SuccessTransformer;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * Class ForgotPasswordController
 * @package App\Http\Controllers\Auth
 *
 *
 * @OA\Post(
 *  path="/passport/forgot",
 *  operationId="forgot",
 *  tags={"Passport"},
 *  summary="Forgot password",
 *  @OA\RequestBody(
 *   @OA\MediaType(
 *    mediaType="multipart/form-data",
 *    @OA\Schema(
 *     type="object",
 *     @OA\Property(property="email", type="string", format="email"),
 *     required={"email"}
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
class ForgotPasswordController extends Controller
{
     use SendsPasswordResetEmails;

    protected function sendResetLinkResponse(Request $request, $response)
    {
        return fractal(true, new SuccessTransformer());
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return fractal(false, new SuccessTransformer());
    }
}
