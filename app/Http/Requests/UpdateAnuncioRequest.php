<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnuncioRequest extends FormRequest
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
            'nome'=>'required|between:2,46',
            'categoria'=>'required',
            'subcategoria'=>'required',
            'preco'=>'required',
            'fotos.*'=>'nullable|mimes:jpg,jpeg,png,bmp|max:4000',
            'descricao'=>'required|between:2,1410'
        ];
    }
}