# bol-wp-tk3-tk4

## development

1. `docker-compose exec myapp composer require jeroennoten/laravel-adminlte`
2. `docker-compose exec myapp php artisan adminlte:install`
3. `docker-compose exec myapp composer require laravel/ui`
4. `docker-compose exec myapp php artisan ui bootstrap --auth`
5. `docker-compose exec myapp php artisan adminlte:install --only=auth_views`
6. `docker-compose exec myapp php artisan adminlte:plugins install --plugin=icheckBootstrap`
7. `docker-compose exec myapp npm install`
8. `docker-compose exec myapp npm run dev` atau `docker-compose exec myapp npm run build`
9. `docker-compose exec myapp composer require spatie/laravel-permission`
10. `docker-compose exec myapp php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"`
11. tambah `use Spatie\Permission\Traits\HasRoles;` ke `app/Models/User.php`
12. tambah permission, role dan user seeder di DatabaseSeeder
13. `docker-compose exec myapp php artisan migrate:fresh --seed`

## local deployment

1. `docker-compose up` or `docker-compose up -d`
2. `docker-compose exec myapp php artisan migrate:fresh --seed`
3. buka `http://localhost:8000` atau `http://0.0.0.0:8000`
4. Login sebagai:
   1. Super Admin:
      1. email: super.admin@laravel.com
      2. password: superadmin1234
   2. Admin:
      1. email: admin@laravel.com
      2. password: admin1234
   3. Staff
      1. email: staff@laravel.com
      2. password: staff1234
   4. Customer:
      1. email: customer@laravel.com
      2. password: customer1234
