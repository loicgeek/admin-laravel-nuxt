<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'city_id', 'image', 'website', 'facebook', 'ig', 'phone', 'email'];


    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'items_categories');
    }

    public function images()
    {
        return $this->hasMany(ItemImage::class, "item_id");
    }
}
