<?php

namespace App\Http\Controllers\API\Social;

use App\Http\Controllers\Controller;
use App\Services\{
    Social\SocialService,
};
use Illuminate\{
    Http\JsonResponse,
    Support\Facades\DB,
};

class SocialBindController extends Controller
{
    public function __construct(
        private readonly SocialService $socialService,
    )
    {
    }

    /**
     * @OA\Delete(
     *     path="/api/social/{social_id}",
     *     tags={"SocialBind"},
     *     operationId="ApiV1SocialUnbind",
     *     summary="Отвязать социальную сеть",
     *     security={
     *         {"bearerAuth": {}},
     *     },
     *     @OA\Parameter(
     *         name="social_id",
     *         in="path",
     *         description="ID соц. сети",
     *         required=true,
     *         example="",
     *         @OA\Schema(
     *             type="integer",
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Аккаутн социальной сети отвязан и удалён из базы",
     *         @OA\JsonContent(ref="#/components/schemas/SocialUnbindResourse")
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Отсутствует токен",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", format="array", default="Unauthenticated"),
     *         )
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Попытка удалить чужой аккаунт",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", format="array", default=false),
     *             @OA\Property(property="message", type="string", format="array", default="Social account is in use by another profile"),
     *         )
     *     ),
     * )
     */
    public function unbind(int $socialId): JsonResponse
    {
        DB::beginTransaction();

        try {
            $this->socialService->unbind($socialId);
            $message = 'Social account has been unbinded';

            DB::commit();
            return $this->successResponse(null, 200, $message);

        } catch (\Exception $exception) {

            DB::rollback();
            return $this->errorResponse($exception);
        }
    }
}
