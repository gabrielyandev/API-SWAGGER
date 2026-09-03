<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Item",
    title: "Cabeçalho de Nota",
    description: "Modelo representando os dados do cabeçalho de nota do banco Firebird",
    required: ["TIPO", "DATA_EMISSAO"],
    properties: [
        new OA\Property(property: "INTERNO", type: "integer", example: 12345),
        new OA\Property(property: "TIPO", type: "string", example: "SAIDA"),
        new OA\Property(property: "PEDIDO_CLIENTE", type: "string", example: "PED-2023-001"),
        new OA\Property(property: "CONTROLE_DE_NOTA", type: "integer", example: 9876),
        new OA\Property(property: "DATA_EMISSAO", type: "string", format: "date-time", example: "2023-10-01 12:00:00"),
        new OA\Property(property: "TOTAL_NOTA", type: "number", format: "float", example: 4500.50),
        new OA\Property(property: "SITUACAO", type: "string", example: "F")
    ]
)]
class Item extends Model
{
    use HasFactory;

    // Conecta exatamente nessa tabela no Firebird
    protected $table = 'CABECALHO_DE_NOTA';

    // Chave primária customizada
    protected $primaryKey = 'INTERNO';

    // Desativa os campos created_at e updated_at (comum em bancos legados)
    public $timestamps = false;

    protected $fillable = [
        'TIPO',
        'PEDIDO_CLIENTE',
        'CONTROLE_DE_NOTA',
        'DATA_EMISSAO',
        'TOTAL_NOTA',
        'SITUACAO',
    ];

    protected $casts = [
        'TOTAL_NOTA' => 'float',
        'DATA_EMISSAO' => 'datetime',
    ];
}
