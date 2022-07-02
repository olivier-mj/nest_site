<?php

namespace App\Services\Search\Typesense;

use GuzzleHttp\Psr7\Query;
use App\Services\Search\SearchResult;
use App\Services\Search\SearchInterface;
use App\Services\Search\Typesense\TypesenseItem;

class TypesenseSearch implements SearchInterface
{
    public function __construct(private readonly TypesenseClient $client)
    {
    }

    public function search(string $q, array $types = [], int $limit = 50, int $page = 1): SearchResult
    {
        $query = [
            'q' => $q,
            'page' => $page,
            'query_by' => 'title,category,content',
            'highlight_full_fields' => 'title,content,category',
            'highlight_affix_num_tokens' => 4,
            'per_page' => $limit,
            'num_typos' => 1,
        ];
        if (!empty($types)) {
            $query['filter_by'] = 'type:['.implode(',', $types).']';
        }

        ['found' => $found, 'hits' => $items] = $this->client->get('collections/content/documents/search?'.Query::build($query));

        return new SearchResult(array_map(fn (array $item) => new TypesenseItem($item), $items), $found > 10 * $limit ? 10 * $limit : $found);
    }
}