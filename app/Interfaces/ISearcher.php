<?php

namespace App\Interfaces;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ISearcher
{
    public function search(string $query = ''): LengthAwarePaginator;
}
