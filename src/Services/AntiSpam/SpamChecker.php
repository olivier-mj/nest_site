<?php

namespace App\Services\AntiSpam;

use App\Entity\Comment;
use Symfony\Contracts\HttpClient\HttpClientInterface;



class SpamChecker
{

    public function __construct(private HttpClientInterface $client, private string $akismetKey)
    {
        $this->client = $client;
        $this->akismetKey = $akismetKey ;
    }

    /**
     * @return int Spam score: 0: not spam, 1: maybe spam, 2: blatant spam
     *
     * @throws \RuntimeException if the call did not work
     */
    public function getSpamScore(Comment $comment, array $context, string $hostname): int
    {
        $response = $this->client->request('POST', "https://{$this->akismetKey}.rest.akismet.com/1.1/comment-check", [
            'body' => array_merge($context, [
                'blog' => "https://{$hostname}",
                'comment_type' => 'comment',
                'comment_author' => $comment->getAuthor(),
                'comment_author_email' => $comment->getEmail(),
                'comment_content' => $comment->getContent(),
                'comment_date_gmt' => $comment->getCreatedAt()->format('c'), // @phpstan-ignore-line
                'blog_lang' => 'fr',
                'blog_charset' => 'UTF-8',
                'is_test' => true,
            ]),
        ]);

        $headers = $response->getHeaders();
        if ('discard' === ($headers['x-akismet-pro-tip'][0] ?? '')) {
            return 2;
        }

        $content = $response->getContent();
        if (isset($headers['x-akismet-debug-help'][0])) {
            throw new \RuntimeException(sprintf('Unable to check for spam: %s (%s).', $content, $headers['x-akismet-debug-help'][0]));
        }

        return 'true' === $content ? 1 : 0;
    }

}
