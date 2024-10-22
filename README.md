# livewire-pc
 
install steps 

    composer install
    cp .env.example .env
    php artisan key:generate
    php artisan migrate
    php artisan serve
    npm install
    npm run dev

    php artisan db:seed --class=RoomSeeder : creates 3 rooms
    php artisan db:seed --class=PCSeeder : creates 10 pcs in every room
    php artisan db:seed --class=UserSeeder : creates 20 users