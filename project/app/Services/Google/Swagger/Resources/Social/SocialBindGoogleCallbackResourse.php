<?php

namespace App\Services\Google\Swagger\Resources\Social;

use App\SharedKernel\Traits\Swagger\{
    StatusSuccessResponseTrait,
};
use Illuminate\{
    Http\Resources\Json\JsonResource,
};

/**
 * @OA\Schema(
 *     title="SocialBindGoogleCallbackResourse",
 *     description="Привязка нового аккаунта google",
 *     @OA\Xml(
 *         name="SocialBindGoogleCallbackResourse"
 *     ),
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/StatusSuccessResponseTrait"),
 *     }
 * )
 */
class SocialBindGoogleCallbackResourse extends JsonResource
{
    use StatusSuccessResponseTrait;

    /**
     * @OA\Property(
     *      property="message",
     *      description="",
     *      type="string",
     *      example="Social account has been binded"
     * )
     */
    public string $message;

    /**
     * @OA\Property(
     *      property="data",
     *      description="",
     *      type="object",
     *      @OA\Property(
     *          property="account",
     *          type="string",
     *          description="Привязанный аккаунт",
     *          ref="#/components/schemas/SocialAccount"
     *      )
     * )
     */
    public array $data;
}
