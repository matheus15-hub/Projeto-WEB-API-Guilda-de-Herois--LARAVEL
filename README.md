<<<<<<< HEAD
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
=======
# Projeto-WEB-API-Guilda-de-Herois
>>>>>>> 5fdd916bba963de9fe946ed551ec06ab523debc7


# CONEXÃO COM O BANCO DE DADOS

## 1. Objetivo

O objetivo desse processo foi conectar o projeto Laravel ao banco de dados PostgreSQL que já existia no Neon e aproveitar as tabelas que estavam prontas. Em vez de criar tudo novamente por meio de migrations, utilizei uma ferramenta para gerar os Models com base na estrutura do banco.

## 2. Configuração do banco

Primeiro, configurei a conexão com o PostgreSQL no arquivo `.env`, informando os dados necessários para acessar o banco. Depois, conferi o arquivo `config/database.php` para ajustar as configurações da conexão `pgsql`.

Como as tabelas estavam dentro de um schema específico, também precisei configurar o `search_path` e a opção `schema` com o nome correto: `Joao_Victor_trabalho_BD`. Essa parte foi importante porque, inicialmente, o gerador não estava encontrando as tabelas.

Para verificar se a conexão estava funcionando, utilizei o comando:

```bash
php artisan db:show
```

Com ele, consegui visualizar informações do banco e confirmar que o Laravel estava conseguindo acessá-lo.

## 3. Instalação e utilização do Reliese

Para gerar os Models sem precisar criá-los manualmente, instalei o pacote Reliese com o seguinte comando:

```bash
composer require reliese/laravel --dev
```

Depois, publiquei o arquivo de configuração do pacote:

```bash
php artisan vendor:publish --tag=reliese-models
```

Esse comando criou o arquivo `config/models.php`, que permite configurar como os Models serão gerados.

Durante os testes, precisei corrigir o nome do schema e limpar o cache das configurações para que o Laravel reconhecesse os valores atualizados. Para isso, utilizei:

```bash
php artisan config:clear
```

Com a configuração corrigida, consegui gerar primeiro o Model da tabela `aventureiro`:

```bash
php artisan code:models --connection=pgsql --schema=Joao_Victor_trabalho_BD --table=aventureiro
```

Depois, utilizei o comando abaixo para gerar os Models das demais tabelas encontradas no schema:

```bash
php artisan code:models --connection=pgsql --schema=Joao_Victor_trabalho_BD
```

Por fim, conferi os arquivos criados com o comando:

```bash
ls -la app/Models
```

## 4. Resultado obtido

Com esse procedimento, foi possível gerar os Models correspondentes às tabelas do projeto, como `Aventureiro`, `Classe`, `Guilda`, `Missao`, `MissoesCompletadas` e `Monstro`.

Esses arquivos ficam no diretório `app/Models` e permitem que o Laravel trabalhe com os registros do banco por meio do Eloquent. Assim, não foi necessário criar migrations para reproduzir as tabelas que já estavam disponíveis no PostgreSQL.

## 5. Conclusão

Esse processo facilitou o desenvolvimento do projeto, pois permitiu aproveitar o banco de dados existente sem precisar montar novamente toda a sua estrutura. O Reliese ajudou a gerar os Models automaticamente, economizando tempo e deixando o projeto preparado para utilizar as tabelas por meio do Laravel.

