<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "UpdateItemRequest",
    title: "Update Item Request",
    description: "Request body para atualização de Item",
    properties: [
        new OA\Property(property: "nome", type: "string", example: "Notebook Atualizado"),
        new OA\Property(property: "descricao", type: "string", example: "Descricao atualizada"),
        new OA\Property(property: "preco", type: "number", format: "float", example: 4800.00),
        new OA\Property(property: "status", type: "string", enum: ["ativo", "inativo"], example: "inativo")
    ]
)]
class UpdateItemRequest extends FormRequest
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
            'nome' => 'sometimes|required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'sometimes|required|numeric|min:0',
            'status' => 'nullable|in:ativo,inativo',
        ];
    }
}
