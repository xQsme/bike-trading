<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'name' => 'required',
            'phone' => 'nullable|numeric',
            'distrito' => 'required',
            'profile_photo' => 'nullable|mimes:jpg,jpeg,png,bmp|max:4000',
            'descricao' => 'nullable'
        ];
    }
}
