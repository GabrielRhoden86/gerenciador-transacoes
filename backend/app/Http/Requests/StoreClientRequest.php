<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients,email',
        ];
    }

    public function messages(): array
    {
            return [
                'name.required'     => 'O campo nome é obrigatório.',
                'email.required'    => 'O campo e-mail é obrigatório.',
                'email.email'       => 'O campo e-mail deve ser válido.',
                'email.unique'      => 'O e-mail informado já está em uso.',
            ];
    }

    protected function failedValidation(Validator $validator)
    {
        Log::warning('Validação falhou no StoreClientRequest', [
            'input' => $this->all(),
            'errors' => $validator->errors()->toArray(),
        ]);

        throw new HttpResponseException(response()->json([
            'message' => 'Erro de validação.',
            'errors' => $validator->errors(),
        ], status: 422));
    }
}
