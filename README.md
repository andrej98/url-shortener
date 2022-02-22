# url-shortener
Url Shortener allows logged in users to create and manage shortened links (like bit.ly), create custom tags and tagging the links with them. It also tracks how many times the shortened link was used.

## Dependencies
Built with PHP 7.3

[Symfony 4.4](https://symfony.com/)

[SonataAdmin 4.8](https://docs.sonata-project.org/)

## How to run
Set up youe .env file with connection to your database.

```bash
composer install
php bin/console doctrine:migrations:migrate
```

Install [Symfony CLI](https://symfony.com/download) and run
```bash
symfony server:start
```
