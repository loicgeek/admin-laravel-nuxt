<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Item extends Model
{
    use HasFactory;

    protected $with = ['city'];

    protected $fillable = ['name', 'city_id', 'user_id', 'image', 'website', 'facebook', 'ig', 'phone', 'email', 'description', 'address', 'is_featured', "hours"];

    protected $hidden = ['city_id', 'user_id'];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'city_id' => 'integer',
        'user_id' => 'integer',
        'ratings_average' => 'double',
        'is_featured' => 'boolean',
        'hours' => 'json',
    ];

    public function city()
    {
        return $this->belongsTo(City::class, "city_id");
    }
    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'items_categories');
    }

    public function images()
    {
        return $this->hasMany(ItemImage::class, "item_id");
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, "item_id");
    }
}
