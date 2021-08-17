<?php


namespace App\Interfaces;


interface IRedisKeyGenerate
{
    public function keyGenerate(?string $id): string;
}
