<?php

namespace App\Services\Twitch;

use App\Services\Cache;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class TwitchClient
{
    private string $clientID;
    private string $clientSecret;
    private HttpClientInterface $client;
    private Cache $cache;

    public function __construct(
        string $clientID,
        string $clientSecret,
        HttpClientInterface $client,
        Cache $cache,
    ) {
        $this->clientID = $clientID;
        $this->clientSecret = $clientSecret;
        $this->client = $client;
        $this->cache = $cache;
    }

    public function getLive(array $streamerID): array
    {

        $token = $this->getToken();



        foreach ($streamerID as $id) {
            $streamerID[] = $id;
        }

    

 

        return $this->api('https://api.twitch.tv/helix/streams', 'GET', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token['access_token'],
                'Client-Id' => $this->clientID
            ],
            'query' => [
                // 'user_id' => $streamerID
                'user_id' => [
                    '150551018',
                    '109773820',
                    '429055487',
                    '93012767',
                    '100949335',
                    '135565655',
                    '49869023',
                    // '59799994', 
                    // '50795214'

                ], 
                'first' =>5 
            ],
        ]);
    }



    public function getReplay(): array
    {
        $token = $this->getToken();

        return $this->cache->get('replay', function (ItemInterface $item) use ($token) {

            // $item->tag(['replay_list']);
            $item->expiresAfter(300);
            return $this->api('https://api.twitch.tv/helix/videos', 'GET', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token['access_token'],
                    'Client-Id' => $this->clientID
                ],
                'query' => [
                    'user_id' =>  '150551018',
                    'first' => '8'
                ]
            ]);
        });
    }

    private function api(string $endpoint, string $method = 'GET', array $options = []): array
    {

        $response = $this->client->request($method, $endpoint, $options);

        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            return $response->toArray();
        }
        throw new TwitchException($response);
    }

    private function getToken(): array
    {

        $options = [
            'query' => [
                'client_id' => $this->clientID,
                'client_secret' => $this->clientSecret,
                'grant_type' => 'client_credentials'
            ],
            'http_version' => '2.0',
        ];

        $endpoint = "https://id.twitch.tv/oauth2/token";

        return $this->cache->get('getToken', function (ItemInterface $item) use ($endpoint, $options) {
            $item->expiresAfter(3600);
            return $this->api($endpoint, 'POST', $options);
        });
    }
}
