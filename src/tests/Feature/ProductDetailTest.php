<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use Stripe\Product;

class ProductDetailTest extends TestCase
{
  use RefreshDatabase;
  /**
   * A basic feature test example.
   *
   * @return void
   */
  public function test_item_detail_page_displays_all_required_information()
  {
    $user = User::factory()->create(['name' => 'テストユーザー']);
    $item = Item::factory()->create([
      'name' => '詳細テスト商品',
      'brand' => 'テストブランド',
      'price' => 1234,
      'description' => 'これはテスト用の説明文です',
      'condition' => '良好'
    ]);

    $category1 = Category::create(['content' => 'カテゴリA']);
    $category2 = Category::create(['content' => 'カテゴリB']);
    $item->categories()->attach([$category1->id, $category2->id]);

    $response = $this->get("/item/{$item->id}");

    $response->assertStatus(200);
    $response->assertSee('詳細テスト商品');
    $response->assertSee('テストブランド');
    $response->assertSee('1,234');
    $response->assertSee('これはテスト用の説明文です');
    $response->assertSee('良好');

    $response->assertSee('カテゴリA');
    $response->assertSee('カテゴリB');
  }

  public function test_multiple_categories_are_displayed()
  {
    $category1 = new Category();
    $category1->content = 'おしゃれ着';
    $category1->save();

    $category2 = new Category();
    $category2->content = '期間限定';
    $category2->save();

    $item = Item::factory()->create([
      'name' => 'テスト用Tシャツ',
    ]);

    $item->categories()->attach([$category1->id, $category2->id]);

    $response = $this->get("/item/{$item->id}");

    $response->assertStatus(200);
    $response->assertSee('おしゃれ着');
    $response->assertSee('期間限定');
  }
}
