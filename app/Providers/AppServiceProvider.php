<?php

namespace App\Providers;

use App\Interfaces\ISearcher;
use App\Services\ElasticsearchSearcher;
use App\Services\ProductsSearcher;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ISearcher::class, function () {
            if (! config('services.search.enabled')) {
                return new ProductsSearcher();
            }
            return new ElasticsearchSearcher(
                $this->app->make(Client::class)
            );
        });
        $this->bindSearchClient();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        JsonResource::withoutWrapping();
    }

    private function bindSearchClient()
    {
        $this->app->bind(Client::class, function ($app) {
            return ClientBuilder::create()
                ->setHosts($app['config']->get('services.search.hosts'))
                ->build();
        });
    }
}
