<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditGroupAuthoritiesRequest extends FormRequest
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
            'newname' => 'max:255',
        ];
    }

    public function messages()
    {
        return [
            'newname.max' => 'キーワードは255文字以内で入力してください。',
        ];
    }
}
