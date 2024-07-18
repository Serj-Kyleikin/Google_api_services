<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @OA\Info(
 *     title="Google API",
 *     version="1.0.0",
 *     @OA\Contact(
 *         email="admin@site.ru"
 *     )
 * )
 * @OA\Server(
 *     url="http://localhost:8876/api/",
 *     description="Локальный сервер"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function successResponse(?array $data, int $code = Response::HTTP_OK, ?string $message = null): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function errorResponse(\Exception $exception): JsonResponse
    {
        $message = $exception->getMessage();
        $code = (strlen($exception->getCode()) === 3) ? $exception->getCode() : Response::HTTP_BAD_REQUEST;

        return response()->json([
            'status' => false, 
            'message' => $message
        ], $code);
    }
}
