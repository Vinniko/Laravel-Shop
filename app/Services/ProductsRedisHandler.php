<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class ProductsRedisHandler extends AbstractRedisHandler
{
    public function keyGenerate(?string $id): string
    {
        return "products";
    }

    public function update(?string $id = null): void
    {
        if ($this->redisIsActive()) {
            $redisKey = $this->keyGenerate($id);
            Redis::del($redisKey);
        }
    }
}
