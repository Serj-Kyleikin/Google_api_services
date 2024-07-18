<?php

namespace App\SharedKernel\Traits\Swagger;

trait DataNullResponseTrait
{
    /**
     * @OA\Schema(
     *     schema="DataNullResponseTrait",
     *     @OA\Property(
     *         property="data",
     *         title="data",
     *         description="Данные в респонсе",
     *         example=null
     *     )
     * )
     */
    public string $data;
}
