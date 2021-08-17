<?php


namespace App\Interfaces;


use Closure;

interface IRedisHandler
{
    /**
     * Обработка кэшируемых данных.
     *
     * @param ?string $id
     * @param Closure $cacheDataCallback
     * @return mixed
     */
    public function handler(?string $id, Closure $cacheDataCallback);

    /**
     * Логика обработки данных при их обновлении в оригинальнйо базе
     *
     * @param string|null $id
     * @return mixed
     */
    public function update(?string $id = null);
}
