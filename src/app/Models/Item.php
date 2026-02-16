<?php

namespace App\Models;

use Dotenv\Parser\Value;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
  use HasFactory;
  protected $fillable = [
    'user_id',
    'name',
    'brand',
    'description',
    'price',
    'condition',
    'img_url',
    'is_sold',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function categories()
  {
    return $this->belongsToMany(Category::class);
  }

  public function comments()
  {
    return $this->hasMany(Comment::class);
  }

  public function likes()
  {
    return $this->hasMany(Like::class);
  }

  public function orders()
  {
    return $this->hasMany(Order::class);
  }

  public function getFormattedPriceAttribute()
  {
    return number_format($this->price);
  }

  public function getImgUrlAttribute($value)
  {
    if (empty($value)) {
      return asset('img/no-image.png');
    }

    if (str_starts_with($value, 'http')) {
      return $value;
    }
    return asset('storage/' . $value);
  }
}
