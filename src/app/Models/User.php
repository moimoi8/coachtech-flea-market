<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
  use HasApiTokens, HasFactory, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'email',
    'password',
    'profile_url',
    'postal_code',
    'address',
    'building',
    'img_url',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  public function items()
  {
    return $this->hasMany(Item::class);
  }

  public function orders()
  {
    return $this->hasMany(Order::class);
  }

  public function orderedItems()
  {
    return $this->hasManyThrough(
      Item::class,
      Order::class,
      'user_id',
      'id',
      'id',
      'item_id',
    );
  }

  public function comments()
  {
    return $this->hasMany(Comment::class);
  }

  public function likes()
  {
    return $this->hasMany(Like::class);
  }

  public function likedItems()
  {
    return $this->belongsToMany(Item::class, 'likes', 'user_id', 'item_id')->withTimestamps();
  }
}
