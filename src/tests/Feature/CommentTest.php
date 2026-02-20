<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
  use RefreshDatabase;
  /**
   * A basic feature test example.
   *
   * @return void
   */
  public function test_logged_user_can_send_comment()
  {
    $user = User::factory()->create();
    $item = Item::factory()->create();
    $commentData = ['comment' => 'これはテストコメントです'];

    $response = $this->actingAs($user)
      ->post("/item/{$item->id}/comment", $commentData);

    $this->assertDatabaseHas('comments', [
      'user_id' => $user->id,
      'item_id' => $item->id,
      'comment' => 'これはテストコメントです',
    ]);

    $response->assertStatus(302);
    $this->get("/item/{$item->id}")->assertSee('これはテストコメントです');
  }

  public function test_guest_user_cannot_send_comment()
  {
    $item = Item::factory()->create();

    $response = $this->post("/item/{$item->id}/comment", ['comment' => '失敗するはずのコメント']);

    $response->assertRedirect('/login');

    $this->assertDatabaseCount('comments', 0);
  }

  public function test_comment_cannot_be_empty()
  {
    $user = User::factory()->create();
    $item = Item::factory()->create();

    $response = $this->actingAs($user)
      ->from("/item/{item->id}")
      ->post("/item/{$item->id}/comment", ['comment' => '']);

    $response->assertSessionHasErrors('comment');
    $response->assertStatus(302);
  }

  public function test_comment_cannot_exceed_255_characters()
  {
    $user = User::factory()->create();
    $item = Item::factory()->create();

    $longComment = str_repeat('あ', 256);

    $response = $this->actingAs($user)
      ->post("/item/{$item->id}/comment", ['comment' => $longComment]);

    $response->assertSessionHasErrors('comment');
  }
}
