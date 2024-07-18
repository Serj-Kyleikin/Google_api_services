<?php

namespace App\Services\Google\Swagger\Virtuals\Models;

/**
 * @OA\Schema(
 *     title="SocialGoogleEventStoreErrorsVirtualModel",
 *     description="Errors Virtual Model",
 *     @OA\Xml(
 *         name="SocialGoogleEventStoreErrorsVirtualModel"
 *     )
 * )
 */
class SocialGoogleEventStoreErrorsVirtualModel
{
    /**
     * @OA\Property(
     *      property="text",
     *      type="array",
     *      @OA\Items(example="The field is required")
     * )
     */
    public string $text;
}
