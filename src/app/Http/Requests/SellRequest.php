<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SellRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image' => 'required|mimes:jpeg,png',
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'detail' => 'required|string|max:225',
            'price' => 'required|integer|min:0',
            'condition_id' => 'required',
            'category_ids'  => 'required',
        ];
    }

    public function messages()
    {
        return [
            'image.required' => '商品画像は必須です',
            'image' => 'jpegかpngで登録してください',
            'name.required' => '商品名は必須です',
            'name.max' => '225文字以内で入力してください',
            'brand.max' => '225文字以内で入力してください',
            'detail.required' => '商品説明は必須です',
            'detail.max' => '225文字以内で入力してください',
            'price.required' => '値段は必須です',
            'price.integer' => '数字で入力してください',
            'price.min' => '0円以上で入力してください',
            'condition_id.required' => 'コンディションを選択してください',
            'category_ids.required' => 'カテゴリーを1つ以上選択してください',
        ];
    }
}
