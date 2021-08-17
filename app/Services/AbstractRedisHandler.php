<?php


namespace App\Services;

use Closure;
use App\Interfaces\IRedisHandler;
use App\Interfaces\IRedisKeyGenerate;
use Illuminate\Support\Facades\Redis;

abstract class AbstractRedisHandler implements IRedisHandler, IRedisKeyGenerate
{
    public function handler(?string $id, Closure $cacheDataCallback): string
    {
        if (!$this->redisIsActive()) {
            return $cacheDataCallback();
        }

        $redisKey = $this->keyGenerate($id);

        $cachedValue = Redis::get($redisKey);

        if ($cachedValue) {
            return $cachedValue;
        }

        $cachedValue = $cacheDataCallback();

        Redis::set($redisKey, $cachedValue, 'EX', config('cache.stores.redis.lifetime'));

        return $cachedValue;
    }

    /**
     * Проверяем подключение к редису.
     *
     * @return bool
     */
    protected function redisIsActive(): bool
    {
        try {
            Redis::connection();

            return true;
        } catch (\RedisException $e) {
            return false;
        }
    }
}
