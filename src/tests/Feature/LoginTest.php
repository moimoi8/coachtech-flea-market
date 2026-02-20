<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
  use RefreshDatabase;
  /**
   * A basic feature test example.
   *
   * @return void
   */

  public function test_email_is_required()
  {
    $response = $this->post('/login', [
      'email' => '',
      'password' => 'password123',
    ]);

    $response->assertSessionHasErrors([
      'email' => 'メールアドレスを入力してください'
    ]);
  }

  public function test_password_is_required()
  {
    $response = $this->post('/login', [
      'email' => 'test@example.com',
      'password' => '',
    ]);

    $response->assertSessionHasErrors([
      'password' => 'パスワードを入力してください'
    ]);
  }

  public function test_login_failed_with_wrong_credentials()
  {
    $response = $this->post('/login', [
      'email' => 'nonexistent@example.com',
      'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors([
      'email' => 'ログイン情報が登録されていません'
    ]);
  }

  public function test_login_success()
  {
    $user = User::factory()->create([
      'password' => bcrypt('password123'),
    ]);

    $response = $this->post('/login', [
      'email' => $user->email,
      'password' => 'password123',
    ]);

    $this->assertAuthenticatedAs($user);

    $response->assertRedirect('/');
  }

  public function test_logout_success()
  {
    $user = User::factory()->create([
      'name' => 'テスト太郎'
    ]);

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();

    $response->assertRedirect('/');
  }
}
