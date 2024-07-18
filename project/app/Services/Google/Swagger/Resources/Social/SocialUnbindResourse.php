<?php

namespace App\Services\Google\Swagger\Resources\Social;

use App\SharedKernel\Traits\Swagger\{
    DataNullResponseTrait,
    StatusSuccessResponseTrait,
};
use Illuminate\{
    Http\Resources\Json\JsonResource,
};

/**
 * @OA\Schema(
 *     title="SocialUnbindResourse",
 *     description="Отвязать социальную сеть",
 *     @OA\Xml(
 *         name="SocialUnbindResourse"
 *     ),
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/StatusSuccessResponseTrait"),
 *     }
 * )
 */
class SocialUnbindResourse extends JsonResource
{
    use StatusSuccessResponseTrait, DataNullResponseTrait;

    /**
     * @OA\Property(
     *      property="message",
     *      description="",
     *      type="string",
     *      example="Social account has been unbinded"
     * )
     */
    public string $message;
}
