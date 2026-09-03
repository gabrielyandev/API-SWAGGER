<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(version: "1.0.0", title: "API de Itens", description: "Documentação da API de Itens para o ambiente Hostinger")]
#[OA\Server(url: 'http://localhost:8000', description: 'Servidor Local')]
#[OA\Server(url: 'https://api.suporteourobras.com', description: 'Servidor de Produção')]
abstract class Controller
{
    //
}
