<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
        $emailValidation = auth()->user() ? 'required|email|max:200' : 'required|email|max:200|unique:users';
        return [
            'email'     => $emailValidation,
            'name'      => 'required|min:3|max:200',
            'address'   => 'required|min:3|max:200',
            'city'      => 'required|min:3|max:200',
            'province'      => 'required|min:3|max:200',
            'postalcode'      => 'required|numeric|min:2',
            'phone'      => 'required|min:7|max:50',
            'name_on_card'      => 'required|min:3|max:200',
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'Ya tienes una cuenta con el correo ingresado. Por favor <a href="/login">inicia sesión</a> para continuar.',
        ];
    }
}
