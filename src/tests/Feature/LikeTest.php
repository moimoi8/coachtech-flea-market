<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LikeTest extends TestCase
{
  use RefreshDatabase;
  /**
   * A basic feature test example.
   *
   * @return void
   */
  public function test_user_can_like_an_item()
  {
    $user = User::factory()->create();
    $this->actingAs($user);

    $item = Item::factory()->create();

    $response = $this->post("/item/{$item->id}/like");

    $this->assertDatabaseHas('likes', [
      'user_id' => $user->id,
      'item_id' => $item->id,
    ]);

    $response->assertStatus(302);
    $followUpResponse = $this->get("/item/{$item->id}");
    $followUpResponse->assertSee('1');
  }

  public function test_liked_icon_displays_pink_heart()
  {
    $user = User::factory()->create();
    $item = Item::factory()->create();

    $user->likedItems()->attach($item->id);

    $response = $this->actingAs($user)->get("/item/{$item->id}");

    $response->assertStatus(200);
    $response->assertSee('images/heart-pink.png');
  }

  public function test_user_can_unlike_an_item()
  {
    $user = User::factory()->create();
    $item = Item::factory()->create();

    $user->likedItems()->attach($item->id);

    $this->actingAs($user);
    $response = $this->post("/item/{$item->id}/like");

    $this->assertDatabaseMissing('likes', [
      'user_id' => $user->id,
      'item_id' => $item->id,
    ]);

    $response->assertStatus(302);
    $followUpResponse = $this->get("/item/{$item->id}");
    $followUpResponse->assertSee('0');
  }
}
