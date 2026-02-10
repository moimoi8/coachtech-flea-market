<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
      'payment_method' => ['required', 'string', 'in:コンビニ払い,カード払い'],
      'postal_code' => ['required', 'string', 'regex:/^\d{3}-\d{4}$/'],
      'address' => ['required', 'string', 'max:255'],
      'building' => ['required', 'string', 'max:255'],
    ];
  }

  public function messages()
  {
    return [
      'payment_method.in' => '支払い方法は「コンビニ払い」か「カード払い」を選択してください',
      'postal_code.required' => '郵便番号を入力してください',
      'postal_code.regex' => '郵便番号はハイフンを含めた8文字で入力してください',
    ];
  }
}
