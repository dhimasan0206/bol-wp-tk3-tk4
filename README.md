# bol-wp-tk3-tk4

## development

1. `docker-compose exec myapp composer require jeroennoten/laravel-adminlte`
2. `docker-compose exec myapp php artisan adminlte:install`
3. `docker-compose exec myapp composer require laravel/ui`
4. `docker-compose exec myapp php artisan ui bootstrap --auth`
5. `docker-compose exec myapp php artisan adminlte:install --only=auth_views`
6. `docker-compose exec myapp php artisan adminlte:plugins install --plugin=icheckBootstrap`
