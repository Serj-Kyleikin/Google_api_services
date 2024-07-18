<?php

namespace App\SharedKernel\Traits\Swagger;

trait StatusSuccessResponseTrait
{
    /**
     * @OA\Schema(
     *     schema="StatusSuccessResponseTrait",
     *     @OA\Property(
     *         property="status",
     *         title="status",
     *         description="Статус респонса",
     *         example=true
     *     )
     * )
     */
    public string $status;
}
