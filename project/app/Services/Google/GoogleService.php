<?php

namespace App\Services\Google;

use Google\Service\Oauth2;
use GuzzleHttp\Client as GuzzleClient;
use App\Services\{
    Social\DTOs\UserInfoDTO,
};
use Exception;
use Illuminate\{
    Http\Response,
};

class GoogleService
{
    public function getUrl(string $callbackUrl): string
    {
        $client = $this->getClient($callbackUrl);
        $authUrl = $client->createAuthUrl();

        return $authUrl;
    }

    public function getClient(string $callbackUrl): \Google_Client
    {
        $configJson = base_path() . '/config/Google/Oauth.json';
        throw_unless(
            file_exists($configJson),
            Exception::class,
            "Your google oauth config is missing. Create and load it from https://console.cloud.google.com/apis/credentials?project=your_project_name",
            Response::HTTP_NOT_FOUND
        );
        $applicationName = 'Google API';

        $client = new \Google_Client();
        $client->setApplicationName($applicationName);
        $client->setAuthConfig($configJson);
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');

        $client->setRedirectUri($callbackUrl);

        $client->setScopes(
            [
                \Google\Service\Oauth2::USERINFO_PROFILE,
                \Google\Service\Oauth2::USERINFO_EMAIL,
                \Google\Service\Oauth2::OPENID,
                \Google\Service\Drive::DRIVE_METADATA_READONLY,
            ]
        );
        $client->setIncludeGrantedScopes(true);

        return $client;
    }

    public function fetchUserData(string $code, string $callbackUrl): UserInfoDTO
    {
        $data = [];
        $client = $this->getClient($callbackUrl);
        $client->fetchAccessTokenWithAuthCode($code);

        $accessToken = $client->getAccessToken();
        $accessToken['grant_type'] = 'urn:ietf:params:oauth:grant-type:jwt-bearer';

        $oauth2Service = new Oauth2($client);
        $userInfo = $oauth2Service->userinfo->get();
        $data['userInfo'] = $userInfo;

        if(false) {

            $guzzleClient = new GuzzleClient();
            $response = $guzzleClient->request('GET', "https://people.googleapis.com/v1/people/{$userInfo->id}?personFields=phoneNumbers,birthdays,genders", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken['access_token'],
                ],
            ]);

            $data['personal'] = json_decode($response->getBody()->getContents(), true);
        }

        return $this->setUserInfoDTO($data);
    }

    private function setUserInfoDTO($data): UserInfoDTO
    {
        $userInfo = $data['userInfo'];
        
        $userInfoDTO = new UserInfoDTO;
        $userInfoDTO->setProviderUserId($userInfo->id);
        $userInfoDTO->setEmail($userInfo->email);
        $userInfoDTO->setName($userInfo->name);

        if(isset($data['personal'])) {

            $personal = $data['personal'];

            $sex = isset($personal['genders'][0]['value']) ? $personal['genders'][0]['value'] : null;
            $userInfoDTO->setSex($sex);

            $birthdate = null;
            $date = isset($personal['birthdays'][0]['date']) ? $personal['birthdays'][0]['date'] : null;
            if(isset($date['year'], $date['month'], $date['day'])) {
                $birthdate = $date['year'] . '-' . $date['month'] . '-' . $date['day'];
            }
            $userInfoDTO->setBirthdate($birthdate);

            $phone = isset($personal['phoneNumbers'][0]['canonicalForm']) ? $personal['phoneNumbers'][0]['canonicalForm'] : null;
            $userInfoDTO->setPhone($phone);
        }

        return $userInfoDTO;
    }
}
