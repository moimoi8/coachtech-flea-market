<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
  public function index()
  {
    return view('auth.login');
  }

  public function store(LoginRequest $request)
  {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
      $request->session()->regenerate();
      return redirect()->intended('/');
    }
    return back()->withErrors([
      'login_error' => 'ログイン情報が登録されていません',
    ])->onlyInput('email');
  }

  public function logout(Request $request)
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
  }
}
