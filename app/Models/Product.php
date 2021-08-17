<?php

namespace App\Models;;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SleepingOwl\Admin\Form\Related\Forms\BelongsTo;
use SleepingOwl\Admin\Form\Related\Forms\HasMany;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'price',
        'qty',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'price' => 'decimal:3',
        'qty' => 'double',
        'options' => 'array'
    ];

    public function options(): BelongsToMany
    {
        return $this->belongsToMany(Option::class, 'options_products')->withPivot('value');
    }
}
