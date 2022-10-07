<?php


namespace App\Http\Controllers\Auth;


use Throwable;
use App\Models\User;
use App\DTOs\OAuthDataDTO;
use OpenApi\Annotations as OA;
use App\Enums\AuthProviderEnum;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Oauth2Resource;
use Illuminate\Auth\Events\Registered;
use App\Services\Auth\AccessTokenAdapter;
use App\Http\Requests\RegistrationRequest;
use App\Transformers\OAuthDataTransformer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class RegistrationController extends Controller
{
    /**
     * @var AccessTokenAdapter
     */
    private AccessTokenAdapter $accessTokenAdapter;

    public function __construct(AccessTokenAdapter $accessTokenAdapter)
    {
        $this->accessTokenAdapter = $accessTokenAdapter;
    }
    
    /**
     * @OA\Post(
     *  path="/passport/registration",
     *  operationId="registration",
     *  tags={"Passport"},
     *  summary="Registration",
     *  @OA\RequestBody(
     *   @OA\MediaType(
     *    mediaType="multipart/form-data",
     *    @OA\Schema(
     *     type="object",
     *     @OA\Property(property="name", type="string"),
     *     @OA\Property(property="email", type="string", format="email"),
     *     @OA\Property(property="password", type="string", format="password"),
     *     required={"email", "password", "name"}
     *    )
     *   )
     *  ),
     *  @OA\Response(
     *   response="200",
     *   description="OK",
     *   @OA\JsonContent(
     *    @OA\Property(property="data", type="object", ref="#/components/schemas/Oauth2Resource")
     *   )
     *  )
     * )
     *
     * @param RegistrationRequest $request
     * @return Oauth2Resource
     * @throws \Exception
     */
    public function __invoke(RegistrationRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = User::create($request->validated());
            event(new Registered($user));
            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();

            throw new BadRequestHttpException("User creation failed", $exception);
        }
        
        $data = $this->accessTokenAdapter->issueToken(AuthProviderEnum::PASSWORD(), [
            'username' => $request->get('email'),
            'password' => $request->get('password')
        ]);

        return fractal(OAuthDataDTO::make($data), new OAuthDataTransformer());
    }
}
