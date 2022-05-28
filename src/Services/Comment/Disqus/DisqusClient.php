<?php

namespace App\Services\Comment\Disqus;

use Symfony\Component\HttpClient\HttpClient;
use App\Services\Comment\Disqus\DisqusException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class DisqusClient
{
    public function __construct(
        private string $publicKey,
        private string $privateKey,
        // private HttpClientInterface $client
    ) {
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
        // $this->client = $client;
    }

    public function getListPost(): mixed
    {
        return $this->api('posts/list');
    }

    /**
     * @param string $endpoint
     * @param string $method
     * @return array
     */
    private function api(string $endpoint, string $method = 'GET'): array
    {


        // "https://disqus.com/api/3.0/<endpoint>.json?api_key=<apiKey>&forum=<forum>&related=thread";
        $url = "https://disqus.com/api/3.0/{$endpoint}.json";

        $client = HttpClient::create();

        $options = [
            'query' => [
                'api_key' => $this->publicKey,
                'api_secret' => $this->privateKey,
                'forum' => 'nest-gaming',
                'sortType' => 'date',
                'related' => 'thread'
            ],
            'http_version' => '2.0'
        ];


        $response = $client->request($method, $url, $options);

        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            return json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        }
        throw new DisqusException($response);

    }


}
