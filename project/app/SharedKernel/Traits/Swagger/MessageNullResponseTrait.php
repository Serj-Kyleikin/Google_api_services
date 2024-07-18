<?php

namespace App\SharedKernel\Traits\Swagger;

trait MessageNullResponseTrait
{
    /**
     * @OA\Schema(
     *     schema="MessageNullResponseTrait",
     *     @OA\Property(
     *         property="message",
     *         title="message",
     *         description="Сообщение в респонсе",
     *         example=null
     *     )
     * )
     */
    public string $message;
}
