# 🚀 API RESTful Laravel com Swagger & Firebird

[![PHP Version](https://img.shields.io/badge/php-%5E8.3-8892BF.svg?style=flat-square&logo=php)](https://www.php.net/)
[![Laravel Version](https://img.shields.io/badge/laravel-11.x-FF2D20.svg?style=flat-square&logo=laravel)](https://laravel.com)
[![Swagger / OpenAPI](https://img.shields.io/badge/OpenAPI-3.0-85EA2D.svg?style=flat-square&logo=swagger)](https://swagger.io/)
[![Laravel Sanctum](https://img.shields.io/badge/Auth-Sanctum-blue.svg?style=flat-square)](https://laravel.com/docs/11.x/sanctum)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=flat-square)](LICENSE)

API RESTful moderna desenvolvida com **Laravel 11**, com autenticação via **Laravel Sanctum**, documentação interativa automatizada com **Swagger (OpenAPI 3.0 via L5-Swagger)** e arquitetura com suporte a múltiplos bancos de dados: **MySQL** (para autenticação e tabelas do sistema) e **Firebird** (para integração com dados legados/ERP - `CABECALHO_DE_NOTA`).

---

## 📌 Sumário

- [Visão Geral e Arquitetura](#-visão-geral-e-arquitetura)
- [Tecnologias Utilizadas](#-tecnologias-utilizadas)
- [Endpoints da API](#-endpoints-da-api)
- [Documentação Swagger](#-documentação-swagger)
- [Pré-requisitos e Extensões](#-pré-requisitos-e-extensões)
- [Instalação e Execução Local](#-instalação-e-execução-local)
- [Configuração de Ambiente (.env)](#-configuração-de-ambiente-env)
- [Deploy em Produção (Hostinger)](#-deploy-em-produção-hostinger)
- [Estrutura do Projeto](#-estrutura-do-projeto)
- [Licença](#-licença)

---

## 🏛 Visão Geral e Arquitetura

O sistema foi arquitetado para fornecer uma camada de API segura, padronizada e documentada:

- **Autenticação & Tokens**: Gerenciados pelo **Laravel Sanctum** através do banco de dados principal (**MySQL**).
- **Dados de Negócio / Itens**: O modelo [`Item`](file:///c:/Users/gabrielyandev/Documents/projects/API%20SWAGGER/app/Models/Item.php) conecta-se diretamente à base legada **Firebird** (`CABECALHO_DE_NOTA`), mantendo chaves personalizadas (`INTERNO`) e desativando timestamps desnecessários do Eloquent.
- **Documentação Dinâmica**: Todas as anotações OpenAPI/Swagger estão centralizadas nos Controllers, Requests e Models, gerando a especificação via CLI.

```
                  ┌──────────────────────┐
                  │    Cliente / Postman │
                  └──────────┬───────────┘
                             │ (HTTPS / Bearer Token)
                             ▼
                  ┌──────────────────────┐
                  │     API Laravel      │
                  │ (Rotas, Auth & Val.) │
                  └──────┬────────┬──────┘
                         │        │
     (Auth / Users / Log)│        │ (Item / Cabeçalho de Nota)
                         ▼        ▼
                ┌───────────┐  ┌──────────────┐
                │   MySQL   │  │   Firebird   │
                └───────────┘  └──────────────┘
```

---

## 🛠 Tecnologias Utilizadas

- **[PHP 8.3+](https://www.php.net/)**
- **[Laravel 11.x](https://laravel.com/)**
- **[Laravel Sanctum](https://laravel.com/docs/11.x/sanctum)** - Autenticação por tokens de API
- **[DarkaOnLine/L5-Swagger](https://github.com/DarkaOnLine/L5-Swagger)** - Integração Swagger UI / OpenAPI 3.0
- **[HarryGulliford/Laravel-Firebird](https://github.com/harrygulliford/laravel-firebird)** - Driver PDO para conexão com Firebird
- **MySQL / MariaDB** - Banco relacional padrão

---

## 🔌 Endpoints da API

Todas as rotas da API possuem o prefixo `/api`.

### 🔑 Autenticação

| Método | Endpoint | Descrição | Autenticação |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/login` | Realiza autenticação com e-mail e senha, retornando o Token Bearer | Pública |
| `GET` | `/api/user` | Retorna os dados do usuário autenticado | `Bearer Token` |

#### Exemplo de requisição de Login (`POST /api/login`):
```json
{
  "email": "admin@example.com",
  "password": "senha_segura"
}
```

#### Resposta de Sucesso (`200 OK`):
```json
{
  "token": "1|laravel_sanctum_token_aqui...",
  "message": "Login realizado com sucesso"
}
```

---

### 📦 Itens / Cabeçalho de Nota

> **Nota**: Todas as operações de `/api/itens` exigem o header `Authorization: Bearer <seu_token>`.

| Método | Endpoint | Descrição | Parâmetros |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/itens` | Lista todos os registros de notas/itens | Nenhum |
| `POST` | `/api/itens` | Registra um novo item/cabeçalho de nota | Body JSON |
| `GET` | `/api/itens/{id}` | Busca os detalhes de um item específico | `id` (código `INTERNO`) |
| `PUT` | `/api/itens/{id}` | Atualiza os dados de um item existente | `id` + Body JSON |
| `DELETE` | `/api/itens/{id}` | Remove um item existente | `id` (código `INTERNO`) |

#### Estrutura do Payload (`Item` / `CABECALHO_DE_NOTA`):
```json
{
  "TIPO": "SAIDA",
  "PEDIDO_CLIENTE": "PED-2023-001",
  "CONTROLE_DE_NOTA": 9876,
  "DATA_EMISSAO": "2023-10-01 12:00:00",
  "TOTAL_NOTA": 4500.50,
  "SITUACAO": "F"
}
```

---

## 📖 Documentação Swagger

A documentação interativa Swagger UI está disponível em:

- **Local**: `http://localhost:8000/api/documentation`
- **Produção**: `https://api.suporteourobras.com/api/documentation`

### Como regenerar a documentação:

Sempre que alterar anotações `#[OA\...]` nos arquivos do projeto, gere a especificação atualizada executando:

```bash
php artisan l5-swagger:generate
```

> [!TIP]
> No ambiente local, você pode definir `L5_SWAGGER_GENERATE_ALWAYS=true` no seu arquivo `.env` para que o Swagger seja recompilado automaticamente a cada requisição. Em produção, mantenha sempre como `false`.

---

## 📋 Pré-requisitos e Extensões

- **PHP >= 8.3**
- **Composer 2.x**
- Extensões PHP obrigatórias ativas no `php.ini`:
  - `pdo_mysql`
  - `pdo_firebird` ou extensão `interbase` (necessária para conexão com a base Firebird)
  - `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `curl`

---

## 💻 Instalação e Execução Local

### 1. Clonar o repositório
```bash
git clone <url-do-repositorio>
cd "API SWAGGER"
```

### 2. Instalar as dependências do Composer
```bash
composer install
```

### 3. Configurar as Variáveis de Ambiente
Copie o arquivo de exemplo e gere a chave da aplicação:
```bash
cp .env.example .env
php artisan key:generate
```

Edite o arquivo `.env` com suas credenciais de banco de dados (MySQL e Firebird).

### 4. Executar as Migrações e Seeders
Crie as tabelas necessárias para o Laravel e os usuários de autenticação:
```bash
php artisan migrate
php artisan db:seed
```

### 5. Gerar a Documentação Swagger
```bash
php artisan l5-swagger:generate
```

### 6. Iniciar o Servidor de Desenvolvimento
```bash
php artisan serve
```

A API estará rodando em `http://localhost:8000`. Acesse `http://localhost:8000/api/documentation` para testar os endpoints interativamente.

---

## ⚙ Configuração de Ambiente (.env)

Principais variáveis utilizadas pelo projeto:

```ini
APP_NAME="API de Itens"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

# Banco de Dados Principal (MySQL - Usuários / Sanctum)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_banco_mysql
DB_USERNAME=usuario_mysql
DB_PASSWORD=senha_mysql

# Conexão com a Base Firebird (CABECALHO_DE_NOTA)
FB_HOST=localhost
FB_PORT=3050
FB_DATABASE=/caminho/para/base.fdb
FB_USERNAME=sysdba
FB_PASSWORD=masterkey
FB_CHARSET=UTF8

# L5-Swagger
L5_SWAGGER_GENERATE_ALWAYS=true  # 'false' em produção
```

---

## 🚀 Deploy em Produção (Hostinger)

Para orientações passo a passo detalhadas sobre hospedagem compartilhada, configuração da pasta `public_html`, comandos via SSH e ajustes de segurança, consulte:

👉 **[INSTRUCOES_HOSTINGER.md](file:///c:/Users/gabrielyandev/Documents/projects/API%20SWAGGER/INSTRUCOES_HOSTINGER.md)**

### Comandos essenciais após upload:

```bash
# Executa migrações no banco de produção
php artisan migrate --force

# Otimiza o carregamento de configurações e rotas
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Gera a documentação Swagger para produção
php artisan l5-swagger:generate
```

---

## 📂 Estrutura do Projeto

Principais diretórios e arquivos customizados:

```text
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Controller.php               # Anotações Globais OpenAPI (Info, Servers, Security)
│   │   │   └── Api/
│   │   │       ├── AuthController.php       # Login e emissão de tokens Sanctum
│   │   │       └── ItemController.php       # CRUD de itens conectado ao Firebird
│   │   └── Requests/
│   │       ├── LoginRequest.php             # Validação de credenciais e Schema OpenAPI
│   │       ├── StoreItemRequest.php         # Validação de criação e Schema OpenAPI
│   │       └── UpdateItemRequest.php        # Validação de atualização e Schema OpenAPI
│   └── Models/
│       ├── Item.php                         # Model Eloquent vinculado à tabela Firebird
│       └── User.php                         # Model de usuários (Sanctum)
├── config/
│   ├── database.php                         # Configuração de conexões (MySQL e Firebird)
│   └── l5-swagger.php                       # Configuração da documentação Swagger
├── database/
│   ├── migrations/                          # Migrations de usuários e tabelas auxiliares
│   └── seeders/DatabaseSeeder.php           # População inicial de usuários
├── routes/
│   └── api.php                              # Definição dos endpoints REST
├── INSTRUCOES_HOSTINGER.md                  # Guia de implantação e deploy na Hostinger
└── README.md                                # Documentação principal do projeto
```

---

## 📄 Licença

Este projeto está sob a licença [MIT](https://opensource.org/licenses/MIT).
