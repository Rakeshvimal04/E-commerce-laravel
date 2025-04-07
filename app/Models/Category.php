<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Don't forget to import Str

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'image', 'is_active'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        }); // Added semicolon here
    } // Closed boot() method

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
