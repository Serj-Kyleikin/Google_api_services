<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\{
    Google\GoogleService,
    Social\SocialService,
};
use App\Services\Google\Utils\GoogleUtils;
use Exception;
use Illuminate\{
    Http\Request,
    Http\Response,
    Support\Facades\DB,
};
use Symfony\Component\HttpFoundation\JsonResponse;

class GoogleOauthController extends Controller
{
    public function __construct(
        private readonly GoogleService $googleService,
        private readonly SocialService $socialService,
    )
    {
    }

    /**
     * @OA\Get(
     *     path="api/login/google",
     *     tags={"Auth"},
     *     operationId="ApiV1LoginGoogleGetLink",
     *     summary="Получить ссылку для авторизации через Google",
     *     @OA\Response(
     *         response="200",
     *         description="Получение ссылки для авторизации через Google",
     *         @OA\JsonContent(ref="#/components/schemas/LoginGoogleGetLinkResourse")
     *     )
     * )
     */
    public function getOauthLink(): JsonResponse
    {
        try {
            $url = GoogleUtils::getOauthURI();
            $data['link'] = $this->googleService->getUrl($url );

            return $this->successResponse($data);

        } catch (\Exception $exception) {

            return $this->errorResponse($exception);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/login/google/callback",
     *     tags={"Auth"},
     *     operationId="ApiV1HandleGoogleCallback",
     *     summary="Колбэк принимающий ответ от Google",
     *     @OA\Parameter(
     *         name="code",
     *         in="query",
     *         description="Код от Google",
     *         required=false,
     *         example="errbg345ye",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Авторизация|Регистрация с авторизацией",
     *         @OA\JsonContent(ref="#/components/schemas/LoginGoogleCallbackTokenResourse")
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Не пришёл код от google",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", format="array", default=false),
     *             @OA\Property(property="message", type="string", format="array", default="Authorization code not found"),
     *         )
     *     )
     * )
     */
    public function handleGoogleAuthorizationCallback(Request $request): JsonResponse
    {
        $code = $request->input('code', null);
        DB::beginTransaction();

        try {
            throw_unless(
                $code,
                Exception::class,
                "Authorization code not found",
                Response::HTTP_BAD_REQUEST
            );

            $url = GoogleUtils::getOauthURI();
            $userInfoDTO = $this->googleService->fetchUserData($code, $url);

            $data = [
                'access_token' => $this->socialService->getToken($userInfoDTO,'google'),
                'token_type' => 'Bearer'
            ];

            DB::commit();
            return $this->successResponse($data);

        } catch (\Exception $exception) {

            DB::rollback();
            return $this->errorResponse($exception);
        }
    }

    /**
     * @OA\Get(
     *     path="api/social/bind/google",
     *     tags={"SocialBind"},
     *     operationId="ApiV1SocialBindGoogleGetLink",
     *     security={
     *         {"bearerAuth": {}},
     *     },
     *     summary="Получить ссылку для привязки аккаунта Google",
     *     @OA\Response(
     *         response="200",
     *         description="Получить ссылку для привязки аккаунта Google",
     *         @OA\JsonContent(ref="#/components/schemas/LoginGoogleGetLinkResourse")
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Отсутствует токен",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", format="array", default="Unauthenticated"),
     *         )
     *     ),
     * )
     */
    public function getOauthBindLink(): JsonResponse
    {
        try {
            $url = GoogleUtils::getBindURI();
            $data['link'] = $this->googleService->getUrl($url);

            return $this->successResponse($data);

        } catch (\Exception $exception) {

            return $this->errorResponse($exception);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/social/bind/google/callback",
     *     tags={"SocialBind"},
     *     operationId="ApiV1SocialBindGoogleCallback",
     *     summary="Колбэк принимающий ответ от Google",
     *     security={
     *         {"bearerAuth": {}},
     *     },
     *     @OA\Parameter(
     *         name="code",
     *         in="query",
     *         description="Код от Google",
     *         required=false,
     *         example="errbg345ye",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Привязка нового аккаунта google",
     *         @OA\JsonContent(ref="#/components/schemas/SocialBindGoogleCallbackResourse")
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Не пришёл код от google",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", format="array", default=false),
     *             @OA\Property(property="message", type="string", format="array", default="Authorization code not found"),
     *         )
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
     *         description="Попытка привязать уже используемый аккаунт",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", format="array", default=false),
     *             @OA\Property(property="message", type="string", format="array", default="Social account is already binded"),
     *         )
     *     ),
     * )
     */
    public function handleGoogleBindCallback(Request $request): JsonResponse
    {
        $code = $request->input('code', null);
        DB::beginTransaction();

        try {
            throw_unless(
                $code,
                Exception::class,
                "Authorization code not found",
                Response::HTTP_BAD_REQUEST
            );

            $url = GoogleUtils::getBindURI();
            $userInfoDTO = $this->googleService->fetchUserData($code, $url);

            $data = [
                'account' => $this->socialService->bind($userInfoDTO, 'google')
            ];
            $message = 'Social account has been binded';

            DB::commit();
            return $this->successResponse($data, 201, $message);

        } catch (\Exception $exception) {

            DB::rollback();
            return $this->errorResponse($exception);
        }
    }
}