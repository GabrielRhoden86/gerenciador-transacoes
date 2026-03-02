<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount'      => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required'    => 'O campo valor é obrigatório.',
            'amount.numeric'     => 'O campo valor deve ser numérico.',
            'amount.min'         => 'O valor deve ser maior que 0.',
            'description.string' => 'A descrição deve ser um texto.',
            'description.max'    => 'A descrição pode ter no máximo 255 caracteres.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        Log::warning('Validação falhou no StoreTransactionRequest', [
            'input' => $this->all(),
            'errors' => $validator->errors()->toArray(),
        ]);

        throw new HttpResponseException(response()->json([
            'message' => 'Erro de validação.',
            'errors' => $validator->errors(),
        ], 422));
    }
}
