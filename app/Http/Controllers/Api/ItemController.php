<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use OpenApi\Attributes as OA;

class ItemController extends Controller
{
    #[OA\Get(
        path: "/api/itens",
        summary: "Lista todos os itens",
        tags: ["Itens"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Listagem de itens",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Item")
                )
            )
        ]
    )]
    public function index()
    {
        return response()->json(Item::all(), 200);
    }

    #[OA\Post(
        path: "/api/itens",
        summary: "Cria um novo item",
        tags: ["Itens"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/StoreItemRequest")
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Item criado com sucesso",
                content: new OA\JsonContent(ref: "#/components/schemas/Item")
            ),
            new OA\Response(response: 422, description: "Erro de validação")
        ]
    )]
    public function store(StoreItemRequest $request)
    {
        $item = Item::create($request->validated());
        return response()->json($item, 201);
    }

    #[OA\Get(
        path: "/api/itens/{id}",
        summary: "Exibe detalhes de um item",
        tags: ["Itens"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Detalhes do item",
                content: new OA\JsonContent(ref: "#/components/schemas/Item")
            ),
            new OA\Response(response: 404, description: "Item não encontrado")
        ]
    )]
    public function show($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['message' => 'Item não encontrado'], 404);
        }

        return response()->json($item, 200);
    }

    #[OA\Put(
        path: "/api/itens/{id}",
        summary: "Atualiza um item existente",
        tags: ["Itens"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: "#/components/schemas/UpdateItemRequest")
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Item atualizado com sucesso",
                content: new OA\JsonContent(ref: "#/components/schemas/Item")
            ),
            new OA\Response(response: 404, description: "Item não encontrado"),
            new OA\Response(response: 422, description: "Erro de validação")
        ]
    )]
    public function update(UpdateItemRequest $request, $id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['message' => 'Item não encontrado'], 404);
        }

        $item->update($request->validated());
        return response()->json($item, 200);
    }

    #[OA\Delete(
        path: "/api/itens/{id}",
        summary: "Remove um item",
        tags: ["Itens"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(response: 204, description: "Item removido com sucesso"),
            new OA\Response(response: 404, description: "Item não encontrado")
        ]
    )]
    public function destroy($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['message' => 'Item não encontrado'], 404);
        }

        $item->delete();
        return response()->json(null, 204);
    }
}
