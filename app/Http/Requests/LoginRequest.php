<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('password') && !$this->has('senha')) {
            $this->merge(['senha' => $this->password]);
        } elseif ($this->has('senha') && !$this->has('password')) {
            $this->merge(['password' => $this->senha]);
        }
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Informe um e-mail válido.',
            'password.required' => 'O campo senha é obrigatório.',
        ];
    }
}