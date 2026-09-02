# Deploy e Execução da API Laravel na Hostinger

Este guia descreve os passos necessários para configurar o banco de dados, gerar a documentação Swagger e preparar a sua API para rodar perfeitamente em um ambiente de hospedagem compartilhada como a Hostinger.

## 1. Configuração do Banco de Dados
Para hospedar o banco no painel da Hostinger:
1. Acesse o hPanel -> **Banco de dados MySQL** e crie um novo banco.
2. Anote o Nome do Banco, Usuário e Senha.
3. No arquivo `.env` do projeto, atualize as credenciais:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1  # Ou localhost
DB_PORT=3306
DB_DATABASE=seu_banco_criado
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

## 2. Geração da Documentação Swagger
Para gerar (ou atualizar) o Swagger de acordo com suas anotações no código, execute no terminal da raiz do projeto:

```bash
php artisan l5-swagger:generate
```
Acesse a documentação através de `http://seu-dominio.com/api/documentation`.

> [!NOTE]
> A configuração `L5_SWAGGER_GENERATE_ALWAYS` está definida como `false` no `.env.example`. Mantenha como `false` em produção (Hostinger) para ganhar performance. Em ambiente local, você pode definir como `true` no `.env` para atualizar o JSON a cada acesso.

## 3. Deploy em Hospedagem Compartilhada (Hostinger)
No ambiente da Hostinger, o diretório servido para a web por padrão é o `public_html`. A maneira correta e segura de hospedar aplicações Laravel em hospedagem compartilhada sem expor arquivos do sistema:

### Opção A: Isolando a pasta `/public` (Recomendado)
1. Faça o upload de **todos** os arquivos do Laravel para uma pasta fora do `public_html` (ex: crie uma pasta `/laravel` na raiz da hospedagem e jogue tudo lá).
2. Mova o conteúdo da pasta `/laravel/public` para dentro do seu `public_html`.
3. Altere o arquivo `index.php` que agora está no `public_html`, ajustando os caminhos para o autoloader:

```php
// public_html/index.php
require __DIR__.'/../laravel/vendor/autoload.php';
$app = require_once __DIR__.'/../laravel/bootstrap/app.php';
```

### Opção B: Mapeamento de Domínio
Se você usa a Hostinger e tem acesso a mudar o diretório do seu domínio ou subdomínio (em Hospedagem de Sites Avançada):
- Configure o diretório raiz do site para apontar diretamente para a pasta `/public` do Laravel.

## 4. Comandos Essenciais Pós-Deploy
Após fazer o upload dos arquivos e subir o banco, acesse o Terminal (SSH) disponível na Hostinger e execute:

```bash
# Executa as migrations para criar a tabela de items
php artisan migrate --force

# Otimiza caches para produção
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

> [!WARNING]
> Nunca faça o commit do arquivo `.env` e evite rodar `composer install` dentro da hospedagem compartilhada se ela tiver limites severos de memória/timeout. Prefira subir a pasta `vendor` caso o SSH falhe ao instalar dependências.
