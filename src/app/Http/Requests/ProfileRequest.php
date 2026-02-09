<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'img_url' => ['nullable', 'image', 'mimes:jpeg,png', 'max:2048'],
      'name' => ['required', 'string', 'max:20'],
      'postal_code' => ['required', 'string', 'regex:/^\d{3}-\d{4}$/'],
      'address' => ['required', 'string', 'max:255'],
      'building' => ['nullable', 'string', 'max:255'],
    ];
  }

  public function messages()
  {
    return [
      'img_url' => '画像ファイルはjpegまたはpng形式でアップロードしてください',
      'name.required' => 'お名前を入力してください',
      'name.max' => 'お名前は20文字以内で入力してください',
      'postal_code.required' => '郵便番号を入力してください',
      'postal_code.regex' => '郵便番号はハイフンを含めた8文字で入力してください',
      'address.required' => '住所を入力してください',
    ];
  }
}
