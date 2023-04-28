# bol-wp-tk3-tk4

## development

1. `docker-compose exec myapp composer require jeroennoten/laravel-adminlte`
2. `docker-compose exec myapp php artisan adminlte:install`
3. `docker-compose exec myapp composer require laravel/ui`
4. `docker-compose exec myapp php artisan ui bootstrap --auth`
5. `docker-compose exec myapp php artisan adminlte:install --only=auth_views`
6. `docker-compose exec myapp php artisan adminlte:plugins install --plugin=icheckBootstrap`
7. `docker-compose exec myapp npm install`
8. `docker-compose exec myapp npm run dev` or `docker-compose exec myapp npm run build`
9. `docker-compose exec myapp composer require spatie/laravel-permission`
10. `docker-compose exec myapp php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"`
11. `docker-compose exec myapp php artisan migrate:fresh`
12. tambah `use Spatie\Permission\Traits\HasRoles;` ke `app/Models/User.php`
13. `docker-compose exec myapp php artisan permission:create-role staff web "manage order|manage product|manage customer"`
14. `docker-compose exec myapp php artisan permission:create-role admin web "manage product|manage user"`
15. `docker-compose exec myapp php artisan permission:create-role customer web "create order"`
