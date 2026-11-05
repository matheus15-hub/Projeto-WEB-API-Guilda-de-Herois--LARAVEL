# 🛡️ Projeto WEB/API — Guilda de Heróis

## 📚 Índice

- [Sobre o projeto](#-sobre-o-projeto)
- [Como acessar a área Web](#-como-acessar-a-área-web)
  - [Entrar com um usuário](#entrar-com-um-usuário)
  - [Criar uma conta](#criar-uma-conta)
  - [Funcionamento das páginas Web](#funcionamento-das-páginas-web)
- [Como utilizar a API](#-como-utilizar-a-api)
  - [Aventureiro](#31-aventureiro)
  - [Missão](#32-missão)
  - [Classe](#33-classe)
  - [Guilda](#34-guilda)
  - [Monstro](#35-monstro)
  - [Missões completadas](#36-missões-completadas)
  - [Utilização do ID retornado](#37-como-utilizar-o-id-retornado-para-atualizar)
  - [Respostas da API](#38-respostas-da-api)
- [Testes automatizados](#-testes-automatizados)

---

## 📖 Sobre o projeto

O Guilda de Heróis é um sistema para auxiliar o secretário de uma guilda no gerenciamento de seus aventureiros e das atividades realizadas por eles. Pelo sistema, é possível cadastrar e consultar aventureiros, organizar classes, monstros e missões, além de registrar quais missões foram concluídas por cada aventureiro.

Cada usuário da área Web fica vinculado a uma guilda. Depois de entrar no sistema, o secretário acessa o painel da sua guilda e acompanha os aventureiros vinculados a ela. Também pode consultar e administrar classes e missões, além de registrar, trocar ou remover o vínculo entre um aventureiro e uma missão.

Além da área Web, o projeto disponibiliza uma API REST que permite consultar, cadastrar, atualizar e excluir informações por meio de requisições HTTP. As respostas da API são enviadas em JSON.

## 🌐 Como acessar a área Web

Com o projeto configurado e o banco de dados conectado, inicie o servidor local:
```bash
php artisan serve
```
Depois, acesse pelo navegador:

- **Página inicial:** `http://127.0.0.1:8000/`

- **Entrar:** `http://127.0.0.1:8000/login`

- **Criar conta:** `http://127.0.0.1:8000/cadastro`

### 2.1. Entrar com um usuário

Na tela de login, informe o **nome do usuário** e a **senha**. O formulário de login utiliza o campo `name`; portanto, o acesso não é feito pelo e-mail.

O `DatabaseSeeder` do projeto cria um usuário de teste com os seguintes dados, desde que a seed tenha sido executada:

| Campo | Valor |
|---|---|
| Nome | `ProfDionatan` |
| Senha | `SenhaMuitoDificil1234` |

| Campo | Valor |
|---|---|
| Nome | `ProfBruno` |
| Senha | `SenhaFacilDemais1234` |

Para criar esse usuário em um ambiente local, execute:
```bash
php artisan db:seed
```
**Atenção:** esse usuário de teste não é associado a uma guilda pelo seeder. Por isso, ele pode entrar no sistema, mas o painel informa que a conta não está vinculada a nenhuma guilda. Para testar o funcionamento completo da área Web, utilize a opção **Cadastre-se** e crie um usuário escolhendo uma guilda existente.

### 2.2. Criar uma conta

Na página `http://127.0.0.1:8000/cadastro`, preencha:

- **Nome:** nome que será utilizado no login;

- **Guilda:** escolha a guilda que o usuário irá administrar;

- **Senha:** informe uma senha com pelo menos 8 caracteres;

- **Confirmar senha:** repita a senha informada.

Depois de concluir o cadastro, o sistema autentica o usuário e direciona automaticamente para o painel (`/dashboard`).

### 2.3. Funcionamento das páginas Web

Depois de entrar, o usuário é direcionado ao painel da guilda. Nele, são exibidos os aventureiros vinculados à guilda do usuário, com informações como nome, classe, idade, experiência e ouro.

As principais áreas são:

- `/dashboard` — painel da guilda e listagem de seus aventureiros;

- `/aventureiros` — cadastro, consulta, edição e exclusão de aventureiros;

- `/classes` — cadastro, consulta, edição e exclusão de classes;

- `/missoes` — consulta das missões e gerenciamento dos aventureiros vinculados a cada missão;

- `/aventureiros/{id}/missoes` — consulta e gerenciamento das missões de um aventureiro específico.

O acesso às páginas internas exige que o usuário esteja autenticado. Para sair do sistema, utilize a opção de sair, que envia a requisição de logout.

## 🔌 Como utilizar a API

Com o servidor iniciado pelo comando `php artisan serve`, o endereço base da API é:
```
http://127.0.0.1:8000/api
```
As rotas podem ser testadas pelo Postman ou Insomnia. Quando a requisição tiver um corpo, selecione o formato JSON e utilize os cabeçalhos:
```http
Accept: application/json
Content-Type: application/json
```
Os IDs utilizados nos exemplos precisam existir no banco de dados.

### 3.1. Aventureiro

#### Rotas disponíveis

| Método    | Rota                                             | Função                                    |
| --------- | ------------------------------------------------ | ----------------------------------------- |
| GET       | `/api/aventureiro`                               | Lista aventureiros                        |
| POST      | `/api/aventureiro`                               | Cadastra aventureiro                      |
| GET       | `/api/aventureiro/{id}`                          | Consulta aventureiro                      |
| PUT/PATCH | `/api/aventureiro/{id}`                          | Atualiza aventureiro                      |
| DELETE    | `/api/aventureiro/{id}`                          | Exclui aventureiro                        |
| PATCH     | `/api/aventureiro/{id}/guilda`                   | Transfere aventureiro de guilda           |
| GET       | `/api/aventureiro/{id}/missoes/concluidas`       | Lista missões concluídas                  |
| GET       | `/api/aventureiro/{id}/missoes/disponiveis`      | Lista missões disponíveis                 |
| POST      | `/api/aventureiro/{id}/missoes/completadas/lote` | Cadastro em lote (pendente no controller) |

#### Cadastrar aventureiro
```http
POST http://127.0.0.1:8000/api/aventureiro
```
```json
{
 "nome": "Gandalf",
 "idade": 60,
 "experiencia": 100,
 "id_classe": 1,
 "id_guilda": 1
}
```
#### Atualizar aventureiro
```http
PUT http://127.0.0.1:8000/api/aventureiro/10
```
```json
{
 "nome": "Gandalf, o Cinzento",
 "idade": 61
}
```
Campos permitidos: `nome`, `idade`, `id_classe` e `id_guilda`. Não é permitido alterar livremente `experiencia` e `ouro`.

#### Transferir aventureiro de guilda
```http
PATCH http://127.0.0.1:8000/api/aventureiro/10/guilda
```
```json
{
 "id_guilda": 2
}
```
Para remover o vínculo, pode enviar `null`:
```json
{
 "id_guilda": null
}
```
### 3.2. Missão

#### Rotas disponíveis

| Método    | Rota               | Função          |
| --------- | ------------------ | --------------- |
| GET       | `/api/missao`      | Lista missões   |
| POST      | `/api/missao`      | Cadastra missão |
| GET       | `/api/missao/{id}` | Consulta missão |
| PUT/PATCH | `/api/missao/{id}` | Atualiza missão |
| DELETE    | `/api/missao/{id}` | Exclui missão   |

#### Cadastrar missão
```http
POST http://127.0.0.1:8000/api/missao
```
```json
{
 "nome": "Derrotar o Dragão",
 "recompensa": 500,
 "id_monstro": 1,
 "quantidade_monstros": 2,
 "dificuldade": "A"
}
```
#### Atualizar missão
```http
PUT http://127.0.0.1:8000/api/missao/5
```
```json
{
 "recompensa": 650,
 "dificuldade": "S"
}
```
### 3.3. Classe

#### Rotas disponíveis

| Método    | Rota               | Função          |
| --------- | ------------------ | --------------- |
| GET       | `/api/classe`      | Lista classes   |
| POST      | `/api/classe`      | Cadastra classe |
| GET       | `/api/classe/{id}` | Consulta classe |
| PUT/PATCH | `/api/classe/{id}` | Atualiza classe |
| DELETE    | `/api/classe/{id}` | Exclui classe   |

#### Cadastrar classe
```http
POST http://127.0.0.1:8000/api/classe
```
```json
{
 "nome": "Mago",
 "descricao": "Especialista em magia",
 "raridade": "A"
}
```
#### Atualizar classe
```http
PUT http://127.0.0.1:8000/api/classe/3
```
```json
{
 "descricao": "Especialista em magia e encantamentos",
 "raridade": "S"
}
```
### 3.4. Guilda

#### Rotas disponíveis

| Método    | Rota                                         | Função                                      |
| --------- | -------------------------------------------- | ------------------------------------------- |
| GET       | `/api/guilda`                                | Lista guildas                               |
| POST      | `/api/guilda`                                | Cadastra guilda                             |
| GET       | `/api/guilda/{id}`                           | Consulta guilda                             |
| PUT/PATCH | `/api/guilda/{id}`                           | Atualiza guilda                             |
| DELETE    | `/api/guilda/{id}`                           | Exclui guilda                               |
| GET       | `/api/guilda/{idGuilda}/ouro`                | Consulta ouro da guilda                     |
| GET       | `/api/guilda/{idGuilda}/missoes-completadas` | Lista missões concluídas pelos aventureiros |

#### Cadastrar guilda
```http
POST http://127.0.0.1:8000/api/guilda
```
```json
{
 "nome": "Ordem dos Magos",
 "lema": "Conhecimento e coragem"
}
```
#### Atualizar guilda
```http
PUT http://127.0.0.1:8000/api/guilda/2
```
```json
{
 "nome": "Ordem dos Grandes Magos",
 "lema": "Sabedoria acima de tudo"
}
```
### 3.5. Monstro

#### Rotas disponíveis

| Método    | Rota                | Função           |
| --------- | ------------------- | ---------------- |
| GET       | `/api/monstro`      | Lista monstros   |
| POST      | `/api/monstro`      | Cadastra monstro |
| GET       | `/api/monstro/{id}` | Consulta monstro |
| PUT/PATCH | `/api/monstro/{id}` | Atualiza monstro |
| DELETE    | `/api/monstro/{id}` | Exclui monstro   |

#### Cadastrar monstro
```http
POST http://127.0.0.1:8000/api/monstro
```
```json
{
 "nome": "Dragão",
 "drop_xp": 100
}
```
#### Atualizar monstro
```http
PUT http://127.0.0.1:8000/api/monstro/4
```
```json
{
 "nome": "Dragão Ancião",
 "drop_xp": 250
}
```
### 3.6. Missões Completadas

Esse recurso registra a ligação entre um aventureiro e uma missão. Ao registrar a conclusão, o sistema consulta a missão e o monstro relacionado para calcular as recompensas de ouro e experiência.

#### Rotas disponíveis

| Método    | Rota                                                | Função                           |
| --------- | --------------------------------------------------- | -------------------------------- |
| GET       | `/api/missoes_completas`                            | Lista missões completadas        |
| POST      | `/api/missoes_completas`                            | Registra missão concluída        |
| GET       | `/api/missoes_completas/{id}`                       | Consulta por ID (ver observação) |
| PUT/PATCH | `/api/missoes_completas/{id}`                       | Atualiza (ver observação)        |
| DELETE    | `/api/missoes_completas/{id}`                       | Exclui (ver observação)          |
| GET       | `/api/missoes_completas/{idAve}/{idMissao}`         | Consulta vínculo                 |
| PUT       | `/api/missoes_completas/{idAve}/{idMissao}`         | Atualiza aventureiro vinculado   |
| DELETE    | `/api/missoes_completas/{idAventureiro}/{idMissao}` | Exclui vínculo                   |

#### Registrar missão concluída
```http
POST http://127.0.0.1:8000/api/missoes_completas
```
```json
{
 "id_missao": 5,
 "id_aventureiro": 10
}
```
#### Atualizar aventureiro associado
```http
PUT http://127.0.0.1:8000/api/missoes_completas/10/5
```
```json
{
 "id_aventureiro": 12
}
```
### Paginação

A listagem de missões completadas possui suporte à paginação, permitindo controlar a quantidade de registros retornados por consulta.

Para definir a quantidade de registros por página, basta utilizar o parâmetro per_page:

GET /api/missoes_completas?per_page=20

O sistema utiliza 10 registros por página como padrão e permite valores entre 1 e 200, evitando consultas com uma quantidade excessiva de registros.
Atenção

As rotas individuais de consulta, atualização e exclusão de missões completadas precisam de ajuste: existe divergência entre os parâmetros das rotas e os esperados pelo controller. A rota de cadastro em lote de missões também aponta para um método ainda não implementado.

### 3.7. Como utilizar o ID retornado para atualizar

Depois de realizar um cadastro, a API retorna o status `201` e os dados do registro criado dentro de `data`.

Por exemplo, se o cadastro retornar o ID `10`, basta utilizá-lo na URL de atualização:
```http
PUT http://127.0.0.1:8000/api/aventureiro/10
```
Envie no JSON somente os campos que deseja alterar e que são permitidos para aquele recurso.

### 3.8. Respostas da API

As respostas seguem, em geral, uma estrutura com os campos `message` e `data`:
```json
{
 "message": "Registro criado com sucesso!",
 "data": {}
}
```
#### Principais códigos HTTP

- `200` — consulta, atualização ou exclusão realizada.

- `201` — cadastro realizado.

- `422` — dados enviados não passaram pela validação.

- `404` — registro não encontrado.

## 🧪 Testes automatizados

#### Executar todos os testes
```
php artisan test
```
#### Executar um arquivo específico
```
php artisan test tests/Feature/Api/ApiClasseTest.php
```
#### Outros arquivos de teste
```
php artisan test tests/Feature/Api/AventureiroApiTest.php
php artisan test tests/Feature/Api/GuildaApiTest.php
php artisan test tests/Feature/Api/MissaoApiTest.php
php artisan test tests/Feature/Api/MissoesCompletadasApiTest.php
php artisan test tests/Feature/Api/MonstroApiTest.php
```
#### Executar um método específico
```
php artisan test --filter=test_criar_aventureiro
```
#### Listar todas as rotas
```
php artisan route:list
```
#### Listar somente as rotas da API
```
php artisan route:list --path=api
```
