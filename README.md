# prueba-tec


# Back
Guia laravel

Ejecutar los siguientes comandos

 - cd prueba-app
 - cp .env.example .env

Copiar y reemplazar en el .env

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=refactorian
DB_USERNAME=refactorian
DB_PASSWORD=refactorian



Ejecutar los siguientes comandos para levantar docker
 - docker-compose up -d --build

Comando para instalar los paquetes de laravel
 - docker-compose exec nginx composer install

Comando para generar la key de laravel
 - docker-compose exec php php artisan key:generate

Comando para realizar la migracion con los seeders
 - docker-compose exec php php artisan migrate:fresh —seed

Comando para realizar los test
 - docker-compose exec php php artisan test


# Front 

Ejecutar los siguientes comandos
 - cd prueba-front

Crear archivo .env.development.local
Copiar y pegar la siguiente linea
 - VITE_API_ENDPOINT = 'http://localhost/api/'

Instalar y ejecutar
 - npm install
 - npm run dev

Usuario Admin por defecto

usuario : admin@mail.com
clave : password
