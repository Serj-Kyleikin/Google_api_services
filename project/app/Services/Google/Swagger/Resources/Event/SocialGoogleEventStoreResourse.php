<?php

namespace App\Services\Google\Swagger\Resources\Event;

use App\SharedKernel\Traits\Swagger\{
    StatusSuccessResponseTrait,
};
use Illuminate\{
    Http\Resources\Json\JsonResource,
};

/**
 * @OA\Schema(
 *     title="SocialGoogleEventStoreResourse",
 *     description="Добавления события в календарь гугл",
 *     @OA\Xml(
 *         name="SocialGoogleEventStoreResourse"
 *     ),
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/StatusSuccessResponseTrait"),
 *     }
 * )
 */
class SocialGoogleEventStoreResourse extends JsonResource
{
    use StatusSuccessResponseTrait;

    /**
     * @OA\Property(
     *      property="message",
     *      description="",
     *      type="string",
     *      example="Event has been created"
     * )
     */
    public string $message;

    /**
     * @OA\Property(
     *      property="data",
     *      description="",
     *      type="object",
     *      @OA\Property(
     *          property="event",
     *          type="string",
     *          description="Привязанный аккаунт",
     *          ref="#/components/schemas/UserCalendarEvents"
     *      )
     * )
     */
    public array $data;
}
