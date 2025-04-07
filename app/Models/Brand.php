<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Import Str

class Brand extends Model
{
    protected $fillable = ['name', 'slug', 'image', 'is_active'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($brand) {
            if (empty($brand->slug)) {
                $brand->slug = Str::slug($brand->name);
            }
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
