<?php

namespace App\Services;

use App\Http\Controllers\ProductController;
use App\Interfaces\ISearcher;
use App\Models\Product;
use Elasticsearch\Client;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class ElasticsearchSearcher implements ISearcher
{
    /** @var Client */
    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function search(string $query = ''): LengthAwarePaginator
    {
        $items = $this->searchOnElasticsearch($query);

        return $this->buildCollection($items);
    }

    private function searchOnElasticsearch(string $query = ''): array
    {
        $product = new Product();
        $items = $this->elasticsearch->search([
            'index' => $product->getSearchIndex(),
            'type' => $product->getSearchType(),
            'body' => [
                'query' => [
                    'multi_match' => [
                        'fields' => ['title^5'],
                        'query' => $query,
                        'fuzziness' => 'AUTO',
                    ],
                ],
            ],
        ]);

        return $items;
    }

    private function buildCollection(array $items): LengthAwarePaginator
    {
        $ids = Arr::pluck($items['hits']['hits'], '_id');

        return Product::with('options')->whereIn('id', $ids)
            ->orderByDesc('created_at')->paginate(ProductController::MAX_PRODUCTS_ON_PAGE);
    }
}
