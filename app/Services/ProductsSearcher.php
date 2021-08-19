<?php

namespace App\Services;

use App\Http\Controllers\ProductController;
use App\Interfaces\ISearcher;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductsSearcher implements ISearcher
{
    public function search(string $query = ''): LengthAwarePaginator
    {
        $products = Product::with('options')->where('title', 'like', "%{$query}%")
            ->orderByDesc('created_at')->paginate(ProductController::MAX_PRODUCTS_ON_PAGE);

        return $products;
    }
}
