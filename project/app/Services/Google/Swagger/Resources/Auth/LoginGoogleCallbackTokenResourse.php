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
 *     title="LoginGoogleCallbackTokenResourse",
 *     description="Авторизация|Регистрация с авторизацией",
 *     @OA\Xml(
 *         name="LoginGoogleCallbackTokenResourse"
 *     ),
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/StatusSuccessResponseTrait"),
 *         @OA\Schema(ref="#/components/schemas/MessageNullResponseTrait"),
 *     }
 * )
 */
class LoginGoogleCallbackTokenResourse extends JsonResource
{
    use StatusSuccessResponseTrait, MessageNullResponseTrait;

    /**
     * @OA\Property(
     *      property="data",
     *      description="",
     *      type="object",
     *      @OA\Property(
     *          property="access_token",
     *          type="string",
     *          description="Токен",
     *          example="10|2f8Fy8onz6De7wJbYi1Qmj9mLbyrb4HOMbh8oG7m99bb8e9f"
     *      ),
     *      @OA\Property(
     *          property="token_type",
     *          type="string",
     *          description="Тип токена",
     *          example="Bearer"
     *      )
     * )
     */
    public array $data;
}
