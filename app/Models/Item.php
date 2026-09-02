<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Item",
    title: "Item",
    description: "Modelo de Item",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "nome", type: "string", example: "Notebook"),
        new OA\Property(property: "descricao", type: "string", example: "Notebook de alta performance"),
        new OA\Property(property: "preco", type: "number", format: "float", example: 4500.50),
        new OA\Property(property: "status", type: "string", enum: ["ativo", "inativo"], example: "ativo"),
        new OA\Property(property: "created_at", type: "string", format: "date-time", example: "2023-10-01T12:00:00Z"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time", example: "2023-10-01T12:00:00Z")
    ]
)]
class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'preco',
        'status',
    ];

    protected $casts = [
        'preco' => 'float',
    ];
}
