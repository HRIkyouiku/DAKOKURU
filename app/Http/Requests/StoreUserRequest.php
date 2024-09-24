<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'fn_jp' => 'required',
            'fn_jp_hira' => 'required',
            'fn_jp_kata' => 'required',
            'fn_en' => 'required',
            'ln_jp' => 'required',
            'ln_jp_hira' => 'required',
            'ln_jp_kata' => 'required',
            'ln_en' => 'required',
            'oln_jp' => 'required_with:oln_jp_hira,oln_jp_kata,oln_jp_en',
            'oln_jp_hira' => 'required_with:oln_jp,oln_jp_kata,oln_jp_en',
            'oln_jp_kata' => 'required_with:oln_jp,oln_jp_hira,oln_jp_en',
            'oln_en' => 'required_with:oln_jp,oln_jp_hira,oln_jp_kata',
            'email' => 'required|email|max:255',
            'password' => 'required|min:4|max:255',
            'employee_no' => 'required|unique:users',
            'joining_date' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attributeを入力してください。',
            'required_with' => ':attributeを入力してください。',
            'email' => ':attributeの形式が正しくありません。',
            'max' => ':attributeは:max文字以内で入力してください。',
            'unique' => 'この:attributeはすでに登録済みです。',
        ];
    }

    public function attributes()
    {
        return [
            'fn_jp' => '名前(正式表示)',
            'fn_jp_hira' => '名前(ひらがな）',
            'fn_jp_kata' => '名前(カタカナ)',
            'fn_en' => '名前(英語)',
            'ln_jp' => '姓(正式表示)',
            'ln_jp_hira' => '姓(ひらがな）',
            'ln_jp_kata' => '姓(カタカナ)',
            'ln_en' => '姓(英語)',
            'oln_jp' => '旧姓の名前(正式表示)',
            'oln_jp_hira' => '旧姓の名前(ひらがな)',
            'oln_jp_kata' => '旧姓の名前(カタカナ)',
            'oln_en' => '旧姓の名前(英語)',
            'email' => 'メールアドレス',
            'password' => 'パスワード',
            'employee_no' => '社員番号',
        ];
    }
}
