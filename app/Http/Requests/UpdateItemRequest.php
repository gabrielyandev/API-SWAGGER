<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "UpdateItemRequest",
    title: "Requisição para Atualização de Cabeçalho de Nota",
    properties: [
        new OA\Property(property: "TIPO", type: "string", example: "SAIDA"),
        new OA\Property(property: "PEDIDO_CLIENTE", type: "string", example: "PED-2023-001"),
        new OA\Property(property: "CONTROLE_DE_NOTA", type: "integer", example: 9876),
        new OA\Property(property: "DATA_EMISSAO", type: "string", format: "date-time", example: "2023-10-01 12:00:00"),
        new OA\Property(property: "TOTAL_NOTA", type: "number", format: "float", example: 4500.50),
        new OA\Property(property: "SITUACAO", type: "string", example: "F")
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
            'TIPO' => 'sometimes|string|max:10',
            'PEDIDO_CLIENTE' => 'nullable|string|max:20',
            'CONTROLE_DE_NOTA' => 'nullable|integer',
            'DATA_EMISSAO' => 'sometimes|date',
            'TOTAL_NOTA' => 'nullable|numeric',
            'SITUACAO' => 'nullable|string|max:30',
        ];
    }
}
