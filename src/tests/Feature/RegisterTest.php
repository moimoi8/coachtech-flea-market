<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
  use RefreshDatabase;
  /**
   * A basic feature test example.
   *
   * @return void
   */
  public function test_example()
  {
    $response = $this->get('/');

    $response->assertStatus(200);
  }

  public function test_name_is_required()
  {
    $response = $this->get('/register');

    $response = $this->post('/register', [
      'name' => '',
      'email' => 'test@example.com',
      'password' => 'password123',
      'password_confirmation' => 'password123',
    ]);

    $response->assertSessionHasErrors(['name' => 'お名前を入力してください']);
  }

  public function test_email_is_required()
  {

    $response = $this->post('/register', [
      'name' => 'テスト太郎',
      'email' => '',
      'password' => 'password123',
      'password_confirmation' => 'password123',
    ]);

    $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
  }

  public function test_password_is_required()
  {

    $response = $this->post('/register', [
      'name' => 'テスト太郎',
      'email' => 'test@example.com',
      'password' => '',
      'password_confirmation' => '',
    ]);

    $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
  }

  public function test_password_length_validation()
  {
    $response = $this->post('/register', [
      'name' => 'テスト太郎',
      'email' => 'test@example.com',
      'password' => '1234567',
      'password_confirmation' => '1234567',
    ]);

    $response->assertSessionHasErrors(['password' => 'パスワードは8文字以上で入力してください']);
  }

  public function test_user_can_register_and_redirect_to_profile_selection()
  {
    $response = $this->post('/register', [
      'name' => 'テストユーザー',
      'email' => 'newuser@example.com',
      'password' => 'password123',
      'password_confirmation' => 'password123',
    ]);

    $this->assertDatabaseHas('users', [
      'email' => 'newuser@example.com',
    ]);

    $response->assertStatus(302);
    $response->assertRedirect('/email/verify');
  }
}
