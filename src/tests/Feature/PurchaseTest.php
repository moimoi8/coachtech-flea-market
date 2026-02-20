<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
  use RefreshDatabase;
  /**
   * A basic feature test example.
   *
   * @return void
   */
  public function test_purchase_can_be_completed()
  {
    $user = User::factory()->create();

    $item = Item::factory()->create(['price' => 1000]);

    $response = $this->actingAs($user)
      ->post("/purchase/{$item->id}", [
        'payment_method' => 'card',
        'stripeToken' => 'tok_visa',
        'postal_code' => '123-4567',
        'address' => '東京都新宿区'
      ]);

    $response->assertSessionHasNoErrors();

    $response->assertStatus(303);
    $this->assertStringContainsString('checkout.stripe.com', $response->headers->get('Location'));
  }

  public function test_item_show_sold_on_index_page()
  {
    $soldItem = Item::factory()->create([
      'name' => '売り切れ商品',
      'is_sold' => true,
    ]);

    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('SOLD');
    $response->assertSee('売り切れ商品');
  }

  public function test_purchased_item_appears_in_profile()
  {
    $user = User::factory()->create();
    $item = Item::factory()->create(['name' => '履歴チェック商品']);

    Order::create([
      'user_id' => $user->id,
      'item_id' => $item->id,
      'payment_method' => 'card',
      'postal_code' => '123-4567',
      'address' => 'テスト住所',
    ]);

    $response = $this->actingAs($user)->get('/mypage?page=buy');

    $response->assertStatus(200);
    $response->assertSee('履歴チェック商品');
  }

  public function test_payment_method_is_reflected_on_purchase_page()
  {
    $user = User::factory()->create();
    $item = Item::factory()->create();

    $response = $this->actingAs($user)
      ->get("/purchase/{$item->id}?payment_method=コンビニ払い");

    $response->assertStatus(200);

    $response->assertSee('コンビニ払い');
  }

  public function test_shipping_address_change_is_reflected()
  {
    $user = User::factory()->create();
    $item = Item::factory()->create();

    $response = $this->actingAs($user)->post(route('item.address.update', ['item_id' => $item->id]), [
      'postal_code' => '999-8888',
      'address' => '大阪府大阪市',
      'building' => 'テストビル'
    ]);

    $response = $this->actingAs($user)->get("/purchase/{$item->id}");

    $response->assertStatus(200);
    $response->assertSee('999-8888');
    $response->assertSee('大阪府大阪市');
  }

  public function test_purchased_item_is_linked_with_shipping_address()
  {
    $user = User::factory()->create();
    $item = Item::factory()->create();

    $order = Order::create([
      'user_id' => $user->id,
      'item_id' => $item->id,
      'payment_method' => 'card',
      'postal_code' => '777-7777',
      'address' => '紐付けテスト住所',
      'building' => '紐付けビル'
    ]);

    $this->assertDatabaseHas('orders', [
      'user_id' => $user->id,
      'item_id' => $item->id,
      'address' => '紐付けテスト住所',
    ]);

    $this->assertEquals($user->id, $order->user->id);
    $this->assertEquals($item->id, $order->item->id);
  }

  public function test_profile_page_shows_correct_info()
  {
    $user = User::factory()->create([
      'name' => 'テスト太郎',
      'profile_url' => 'test_avatar.jpg'
    ]);

    $sellItem = Item::factory()->create(['user_id' => $user->id, 'name' => '出品した商品']);
    $order = Order::create([
      'user_id' => $user->id,
      'item_id' => Item::factory()->create(['name' => '購入した商品'])->id,
      'payment_method' => 'card',
      'postal_code' => '000-0000',
      'address' => '住所',
    ]);

    $response = $this->actingAs($user)->get('/mypage');

    $response->assertStatus(200);
    $response->assertSee('テスト太郎');
    $response->assertSee('出品した商品');
    $response->assertSee('test_avatar.jpg');

    $this->actingAs($user)->get('/mypage?page=buy')->assertSee('購入した商品');
  }

  public function test_profile_edit_page_has_initial_values()
  {
    $user = User::factory()->create([
      'name' => '初期名前',
      'postal_code' => '000-0000',
      'address' => '埼玉県さいたま市',
      'profile_url' => 'profiles/test_user.jpg'
    ]);

    $response = $this->actingAs($user)->get('/mypage/profile');

    $response->assertStatus(200);
    $response->assertSee('初期名前');
    $response->assertSee('000-0000');
    $response->assertSee('埼玉県さいたま市');
    $response->assertSee('profiles/test_user.jpg');
  }

  public function test_item_can_be_published()
  {
    $user = User::factory()->create();
    $category = Category::factory()->create(['content' => 'ファッション']);

    $response = $this->actingAs($user)->post('/sell', [
      'name' => 'テスト出品商品',
      'description' => '商品の説明文です',
      'price' => 5000,
      'condition' => '良好',
      'category_ids' => [$category->id],
      'brand' => 'テストブランド',
      'img_url' => UploadedFile::fake()->create('item.jpg', 100),
    ]);

    $response->assertRedirect('/');

    $this->assertDatabaseHas('items', [
      'name' => 'テスト出品商品',
      'price' => 5000,
      'user_id' => $user->id,
    ]);
  }

  public function test_verification_email_is_sent_after_registration()
  {
    Notification::fake();

    $response = $this->post('/register', [
      'name' => 'テストユーザー',
      'email' => 'test@example.com',
      'password' => 'password123',
      'password_confirmation' => 'password123',
    ]);

    $user = \App\Models\User::where('email', 'test@example.com')->first();
    Notification::assertSentTo($user, VerifyEmail::class);
  }

  public function test_email_can_be_verified_and_redirects_to_profile_setting()
  {
    $user = User::factory()->create([
      'email_verified_at' => null,
    ]);

    $verificationUrl = URL::temporarySignedRoute(
      'verification.verify',
      now()->addMinutes(60),
      ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $response = $this->actingAs($user)->get($verificationUrl);

    $response->assertRedirect('/mypage/profile');

    $this->assertNotNull($user->fresh()->email_verified_at);
  }
}
