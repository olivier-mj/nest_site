<?php

namespace App\Services\Comment\Disqus;

use Symfony\Contracts\HttpClient\ResponseInterface;

final class DisqusException extends \RuntimeException
{
    public int $status;

    /**
     * @var string
     */
    public $message;

    public function __construct(ResponseInterface $response)
    {
        $this->status = $response->getStatusCode();
        $this->message = json_decode($response->getContent(false), true, 512, JSON_THROW_ON_ERROR)['message'] ?? '';
    }
}
