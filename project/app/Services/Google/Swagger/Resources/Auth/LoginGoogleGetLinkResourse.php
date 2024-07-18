<?php

namespace App\Services\Google\Swagger\Resources\Auth;

use App\SharedKernel\Traits\Swagger\{
    MessageNullResponseTrait,
    StatusSuccessResponseTrait,
};
use Illuminate\{
    Http\Resources\Json\JsonResource,
};

/**
 * @OA\Schema(
 *     title="LoginGoogleGetLinkResourse",
 *     description="Получить ссылку для авторизации через Google",
 *     @OA\Xml(
 *         name="LoginGoogleGetLinkResourse"
 *     ),
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/StatusSuccessResponseTrait"),
 *         @OA\Schema(ref="#/components/schemas/MessageNullResponseTrait"),
 *     }
 * )
 */
class LoginGoogleGetLinkResourse extends JsonResource
{
    use StatusSuccessResponseTrait, MessageNullResponseTrait;

    /**
     * @OA\Property(
     *      property="data",
     *      description="",
     *      type="object",
     *      @OA\Property(
     *          property="link",
     *          type="string",
     *          description="Ссылка для авторизации через Google",
     *          example="https://accounts.google.com/o/oauth2/v2/auth?response_type=code"
     *      )
     * )
     */
    public array $data;
}
