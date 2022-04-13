<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApiService
{
    public function __construct(private HttpClientInterface  $client)
    {
        $this->client = $client;
    }

    public function getPosts(): array
    {
        $response = $this->client->request(
            'GET',
            'http://192.168.1.100:1337/posts'
        );

        return $response->toArray();
    }

    public function getPost(int $id): array
    {
        $response = $this->client->request(
            'GET',
            'http://192.168.1.100:1337/posts/' + $id
        );

        return $response->toArray();
    }

}
