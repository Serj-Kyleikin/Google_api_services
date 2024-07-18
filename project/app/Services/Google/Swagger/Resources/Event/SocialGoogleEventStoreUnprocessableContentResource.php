<?php

namespace App\Services\Google\Swagger\Resources\Event;

use App\Services\Google\Swagger\Virtuals\Models\SocialGoogleEventStoreErrorsVirtualModel;

/**
 * @OA\Schema(
 *     title="SocialGoogleEventStoreUnprocessableContentResource",
 *     description="Unprocessable content resource",
 *     @OA\Xml(
 *         name="SocialGoogleEventStoreUnprocessableContentResource"
 *     )
 * )
 */
class SocialGoogleEventStoreUnprocessableContentResource
{
    /**
     * @OA\Property(
     *     title="errors",
     *     description="errors",
     * )
     */
    public SocialGoogleEventStoreErrorsVirtualModel $errors;
}
