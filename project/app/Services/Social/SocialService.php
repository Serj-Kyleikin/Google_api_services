<?php

namespace App\Services\Social;

use App\Models\{
    User,
    User\SocialAccount,
    User\Phone,
};
use App\Services\{
    Phone\PhoneService,
    Social\DTOs\UserInfoDTO,
};
use Exception;
use Illuminate\{
    Support\Facades\Hash,
    Support\Str,
    Http\Response,
};

class SocialService
{
    public function __construct(
        private readonly PhoneService $phoneService,
    )
    {
    }

    public function getToken(UserInfoDTO $userInfoDTO, string $provider): string
    {
        $providerUserId = $userInfoDTO->getProviderUserId();

        $socialAccount = SocialAccount::where([
            'provider' => $provider,
            'provider_user_id' => $providerUserId
        ])->first();

        $user = $socialAccount
            ? User::find($socialAccount->user_id)
            : $this->register($providerUserId, $userInfoDTO, $provider);

        return $user->createToken('auth_token')->plainTextToken;
    }

    /**
     * @throws \Throwable
     */
    private function register(string $providerUserId, UserInfoDTO $userInfoDTO, string $provider): User
    {
        $email = $userInfoDTO->getEmail();
        $phone = $userInfoDTO->getPhone();

        $user = User::where('email', $email)->first();

        if($user == null) {

            $user = User::create([
                'name' => $userInfoDTO->getName(),
                'email' => $email,
                'email_verified_at' => now(),
                'password' => Hash::make(Str::password(10)),
                'sex' => $userInfoDTO->getSex(),
                'birthdate' => $userInfoDTO->getBirthdate()
            ]);

            if($phone && $this->phoneService->isNotRegistered($phone)) {
                Phone::create(['phone' => $phone]);
            }
        }

        SocialAccount::create([
            'provider' => $provider,
            'provider_user_id' => $providerUserId,
            'user_id' => $user->id
        ]);

        return $user;
    }

    /**
     * @throws \Throwable
     */
    public function bind(UserInfoDTO $userInfoDTO, string $provider): SocialAccount
    {
        $providerUserId = $userInfoDTO->getProviderUserId();
        $userId = auth()->id();

        $socialAccount = SocialAccount::where([
            'provider_user_id' => $providerUserId,
            'provider' => $provider
        ])->first();

        throw_if(
            $socialAccount,
            Exception::class,
            "Social account is already binded",
            Response::HTTP_FORBIDDEN
        );

        return SocialAccount::create([
            'provider' => $provider,
            'provider_user_id' => $providerUserId,
            'user_id' => $userId
        ]);
    }

    /**
     * @throws \Throwable
     */
    public function unbind(int $socialId): void
    {
        $socialAccount = SocialAccount::find($socialId);

        throw_if(
            $socialAccount->user_id != auth()->id(),
            Exception::class,
            "Social account is in use by another profile",
            Response::HTTP_FORBIDDEN
        );

        $socialAccount->delete();
    }
}
