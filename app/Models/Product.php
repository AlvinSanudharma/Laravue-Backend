<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'type',
        'description', 'price', 'quantity'
    ];

    protected $hidden = [];

    public function galleries()
    {
        // return $this->hasMany(ProductGallery::class, 'products_id');
    }
}
