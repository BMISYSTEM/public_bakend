<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Clientes extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'telefono'=>['required','numeric','unique:clientes,telefono'],
            'email'=>['required','email','unique:clientes,email'],
            'nombre'=>['required'],
            'apellido'=>['required'],
            'email'=>['required'],
            'cedula'=>['required'],
            'data'=>['nullable'],
            //
        ];
    }
    public function messages()
    {
        return [
            'telefono.required'=>'debe ingresar un telefono',
            'telefono.unique'=>'el telefono ya esta registrado',
            'telefono.numeric'=>'debe ingresar un telefono valido',
            'email.required'=>'debe ingresar un correo',
            'email.email'=>'debe ingresar un correo valido',
            'email.unique'=>'el email ya esta registrado'
        ];
    }
}
