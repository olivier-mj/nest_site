<?php

namespace App\Services\Search;

interface SearchInterface
{
    /**
     * @param string[] $types
     */
    public function search(string $q, array $types = [], int $limit = 50, int $page = 1): SearchResult;
}