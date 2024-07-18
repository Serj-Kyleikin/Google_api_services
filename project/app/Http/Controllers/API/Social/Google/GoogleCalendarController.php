<?php

namespace App\Http\Controllers\API\Social\Google;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Social\Google\{
    GoogleCreateEventRequest,
};
use App\Services\{
    Google\GoogleCalendarService,
};
use App\Services\Google\DTOs\EventDTO;
use Carbon\Carbon;
use Illuminate\{
    Support\Facades\DB,
};
use Symfony\{
    Component\HttpFoundation\JsonResponse,
};

class GoogleCalendarController extends Controller
{
    public function __construct(
        private readonly GoogleCalendarService $googleCalendarService,
    )
    {
    }

    /**
     * @OA\Post(
     *     path="/api/social/{social_id}/google/event",
     *     operationId="ApiV1SocialGoogleEventStore",
     *     tags={"Events"},
     *     summary="Добавления события в календарь гугл",
     *     security={
     *         {"bearerAuth": {}},
     *     },
     *     @OA\Parameter(
     *         name="social_id",
     *         in="path",
     *         description="ID аккаунта соц. сети",
     *         required=true,
     *         example="",
     *         @OA\Schema(
     *             type="integer",
     *         ),
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"text"},
     *                 @OA\Property(property="start_date", type="string", example="2024-06-13 11:00"),
     *                 @OA\Property(property="end_date", type="string", example="2024-06-13 12:00"),
     *                 @OA\Property(property="text", type="string", example="Текст в календаре"),
     *                 @OA\Property(property="is_conference", type="integer", example=1),
     *                 @OA\Schema(ref="#/components/schemas/GoogleCreateEventRequest")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Событие создано",
     *         @OA\JsonContent(ref="#/components/schemas/SocialGoogleEventStoreResourse")
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
     *         description="Попытка создать событие не в google аккаунте",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", format="array", default="This social account cant' be used for google api"),
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Не найден аккаунт",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", format="array", default="Social account not found"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Content",
     *         @OA\JsonContent(ref="#/components/schemas/SocialGoogleEventStoreUnprocessableContentResource")
     *     )
     * )
     */
    public function createEvent(int $socialId, GoogleCreateEventRequest $request): JsonResponse
    {
        $startDate = $request->input('start_date', Carbon::parse(now())->toDateString());
        $endDate = $request->input('end_date', Carbon::parse(now())->addHour()->toDateString());
        $isConference = $request->input('is_conference', false);
        $text = $request->input('text');

        DB::beginTransaction();

        try {
            $eventDTO = new EventDTO;
            $eventDTO->setSocialId($socialId);
            $eventDTO->setStartDate($startDate);
            $eventDTO->setEndDate($endDate);
            $eventDTO->setText($text);
            $eventDTO->setIsConference($isConference);

            $data['event'] = $this->googleCalendarService->createEvent($eventDTO);
            $message = 'Event has been created';

            DB::commit();
            return $this->successResponse($data, 201, $message);

        } catch (\Exception $exception) {

            DB::rollback();
            return $this->errorResponse($exception);
        }
    }
}