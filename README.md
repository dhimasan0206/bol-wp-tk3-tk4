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
14. `docker-compose exec myapp php artisan make:model Product -a`
15. `docker-compose exec myapp php artisan make:controller UserController --model=User --resource --requests`
16. `docker-compose exec myapp php artisan make:policy UserPolicy --model=User`
17. `docker-compose exec myapp php artisan make:controller CustomerController --model=User --resource --requests`
18. `docker-compose exec myapp php artisan make:policy CustomerPolicy --model=User`
19. `docker-compose exec myapp php artisan make:model Cart -a`
20. `docker-compose exec myapp php artisan make:model Order -a`
21. `docker-compose exec myapp php artisan make:model OrderItem -a`

## local deployment

1. `docker-compose up` or `docker-compose up -d`
2. `docker-compose exec myapp php artisan migrate:fresh --seed`
3. buka `http://localhost:8000` atau `http://0.0.0.0:8000`
4. Login sebagai:
   1. Super Admin:
      1. email: super.admin@laravel.com
      2. password: superadmin1234
      3. Bisa semuanya
   2. Admin:
      1. email: admin@laravel.com
      2. password: admin1234
      3. Bisa manajemen user dan produk
   3. Staff
      1. email: staff@laravel.com
      2. password: staff1234
      3. Bisa manajemen pembeli, produk, dan pesanan
   4. Customer:
      1. email: customer@laravel.com
      2. password: customer1234
      3. Bisa membuat dan membatalkan pesanan
5. Tambah produk sebagai admin atau staff
6. Tambah produk ke keranjang sebagai customer
7. Buat pesanan sebagai customer
8. Selesaikan pesanan sebagai staff
