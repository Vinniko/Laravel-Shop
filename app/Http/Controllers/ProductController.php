<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\InfoResource;
use App\Http\Resources\ProductResource;
use App\Models\Option;
use App\Models\Product;
use App\Services\ProductsRedisHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductController extends Controller
{
    const MAX_PRODUCTS_ON_PAGE = 40;
    private $delete_message = 'Продукт был успешно удален.';

    public function index(ProductsRedisHandler $productsRedisHandler): JsonResource
    {
        dd(Option::with('values')->find(1));
        $products = unserialize($productsRedisHandler->handler(null, function (){
            $products =  Product::with('options')->orderByDesc('created_at')
                ->paginate(self::MAX_PRODUCTS_ON_PAGE);

            return serialize(ProductResource::collection($products));
        }));

        return $products;
    }

    /**
     * Фильтрация списка товаров по опциям и их значениям
     * пример запроса: http://127.0.0.1:8000/api/products/filter?options[option_name][]=value&
     * @param Request $request
     * @return JsonResource
     */
    public function filter(Request $request): JsonResource
    {
        if (!$request->has('options')) {
            $products = Product::with('options')->orderByDesc('created_at')
                ->paginate(self::MAX_PRODUCTS_ON_PAGE);

            return ProductResource::collection($products);
        }

        $options = $request->all()['options'];
        $query = "distinct product_id from options_products where ";

        foreach ($options as $key => $option) {
            foreach ($option as $value) {
                $query = $query . sprintf("options_products.product_id in
               (SELECT options_products.product_id from options_products
               inner join options on options.id = options_products.option_id
               where options.title = '%s' and options_products.value = '%s') and ", $key, $value);
            }
        }

        $query = rtrim($query, " and ");
        $products = Product::with('options')->whereIn('id', function ($filter_query) use ($query) {
            $filter_query->selectRaw($query);
        })->paginate(self::MAX_PRODUCTS_ON_PAGE);

        return ProductResource::collection($products);
    }

    /**
     * Получение конкретного товара
     * @param Product $product
     * @return JsonResource
     */
    public function show(Product $product): JsonResource
    {
        return new ProductResource($product);
    }

    /**
     * Создание товара
     * @param ProductStoreRequest $request
     * @return JsonResource
     */
    public function store(ProductStoreRequest $request, ProductsRedisHandler $productsRedisHandler): JsonResource
    {
        $product = Product::firstOrCreate([
            'title' => $request['title'],
            'price' => $request['price'],
            'qty' => $request['qty'],
        ]);

        if ($request->has('options')) {
            $this->setOptions($product, $request['options']);
        }

        $productsRedisHandler->update();

        return new ProductResource($product);
    }

    /**
     * Изменение товара
     * @param ProductUpdateRequest $request
     * @param Product $product
     * @return JsonResource
     */
    public function edit(ProductUpdateRequest $request, Product $product, ProductsRedisHandler $productsRedisHandler): JsonResource
    {
        $product->update($request->all());

        if ($request->has('options')) {
            $this->setOptions($product, $request['options']);
        }

        $productsRedisHandler->update();

        return new ProductResource($product);
    }

    /**
     * Удаление товара
     * @param Product $product
     * @return JsonResource
     */
    public function delete(Product $product, ProductsRedisHandler $productsRedisHandler): JsonResource
    {
        $product->delete();
        $productsRedisHandler->update();

        return new InfoResource([
            'message' => $this->delete_message,
        ]);
    }

    /**
     * Добавление опций к товару
     * @param Product $product
     * @param array $options
     */
    private function setOptions(Product $product, array $options): void
    {
        $tmp = [];

        foreach ($options as $option) {
            $tmp[Option::firstOrCreate([
                'title' => $option['title'],
            ])->id] = array('value' => $option['value']);
        }

        $product->options()->sync($tmp);
    }
}
