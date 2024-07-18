<?php

namespace App\Services\Google\Swagger\Resources\Social;

use App\SharedKernel\Traits\Swagger\{
    MessageNullResponseTrait,
    StatusSuccessResponseTrait,
};
use Illuminate\{
    Http\Resources\Json\JsonResource,
};

/**
 * @OA\Schema(
 *     title="SocialBindGoogleGetOauthLinkResourse",
 *     description="Получить ссылку для авторизации через Google",
 *     @OA\Xml(
 *         name="SocialBindGoogleGetOauthLinkResourse"
 *     ),
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/StatusSuccessResponseTrait"),
 *         @OA\Schema(ref="#/components/schemas/MessageNullResponseTrait"),
 *     }
 * )
 */
class SocialBindGoogleGetOauthLinkResourse extends JsonResource
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
     *          description="Ссылка для привязки аккаунта Google",
     *          example="https://accounts.google.com/o/oauth2/v2/auth?response_type=code"
     *      )
     * )
     */
    public array $data;
}
