<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "StoreItemRequest",
    title: "Store Item Request",
    description: "Request body para criação de Item",
    required: ["nome", "preco"],
    properties: [
        new OA\Property(property: "nome", type: "string", example: "Notebook"),
        new OA\Property(property: "descricao", type: "string", example: "Notebook de alta performance"),
        new OA\Property(property: "preco", type: "number", format: "float", example: 4500.50),
        new OA\Property(property: "status", type: "string", enum: ["ativo", "inativo"], example: "ativo")
    ]
)]
class StoreItemRequest extends FormRequest
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
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric|min:0',
            'status' => 'nullable|in:ativo,inativo',
        ];
    }
}
