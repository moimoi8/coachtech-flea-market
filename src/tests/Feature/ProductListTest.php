<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class ProductListTest extends TestCase
{
  use RefreshDatabase;
  /**
   * A basic feature test example.
   *
   * @return void
   */
  public function test_can_get_all_products()
  {
    $user = User::factory()->create(['name' => 'テスト太郎']);

    Item::factory()->count(2)->create();

    $response = $this->get('/');

    $response->assertStatus(200);
  }

  public function test_purchased_item_is_marked_as_sold()
  {
    $user = User::factory()->create(['name' => '購入者']);
    $item = Item::factory()->create([
      'is_sold' => false
    ]);

    $this->actingAs($user)
      ->get("/purchase/success/{$item->id}");

    $this->assertDatabaseHas('items', [
      'id' => $item->id,
      'is_sold' => 1,
    ]);
  }

  public function test_own_items_are_not_displayed_in_list()
  {
    $me = User::factory()->create(['name' => '自分']);
    $others = User::factory()->create(['name' => '他人']);

    $myItem = Item::factory()->create([
      'user_id' => $me->id,
      'name' => '私の出品したバッグ'
    ]);

    $otherItem = Item::factory()->create([
      'user_id' => $others->id,
      'name' => '他人の出品した服'
    ]);

    $response = $this->actingAs($me)->get('/');

    $response->assertStatus(200);

    $response->assertSee('他人の出品した服');

    $response->assertDontSee('私の出品したバッグ');
  }

  public function test_only_liked_items_are_displayed_in_mylist()
  {
    $user = User::factory()->create(['name' => 'マイリストユーザー']);
    $item = Item::factory()->create(['name' => 'お気に入りバッグ']);

    $user->likedItems()->attach($item->id);

    $response = $this->actingAs($user)->get('/?tab=mylist');

    $response->assertStatus(200);
    $response->assertSee('お気に入りバッグ');
  }

  public function test_sold_label_is_displayed_on_purchased_items_in_mylist()
  {
    $user = User::factory()->create(['name' => 'テスト会員']);
    $item = Item::factory()->create([
      'name' => '売れたバッグ',
      'is_sold' => true,
    ]);

    $user->likedItems()->attach($item->id);

    $response = $this->actingAs($user)->get('/?tab=mylist');

    $response->assertStatus(200);
    $response->assertSee('売れたバッグ');
    $response->assertSee('SOLD');
  }

  public function test_unauthenticated_user_cannot_see_items_in_mylist()
  {
    $item = Item::factory()->create(['name' => '見えてはいけない商品']);

    $response = $this->get('/?tab=mylist');

    $response->assertStatus(200);
    $response->assertDontSee('見えてはいけない商品');
  }

  public function test_can_search_items_by_partial_name()
  {
    Item::factory()->create(['name' => 'コーヒー豆']);
    Item::factory()->create(['name' => '紅茶セット']);

    $response = $this->get('/?keyword=コーヒー');

    $response->assertStatus(200);
    $response->assertSee('コーヒー豆');
    $response->assertDontSee('紅茶セット');
  }

  public function test_search_keyword_is_retained_in_mylist_tab()
  {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
      ->get('/?tab=mylist&keyword=コーヒー');

    $response->assertStatus(200);
    $response->assertSee('value="コーヒー"', false);
  }
}
