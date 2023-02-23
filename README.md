<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>


# Prueba Zip Back - Backend Developer 2023

El reto consiste en, utilizar el framework Laravel para replicar la funcionalidad de esta API
(https://jobs.backbonesystems.io/api/zip-codes/01210), utilizando esta fuente de información.
El tiempo de respuesta promedio debe ser menor a 300 ms, pero entre menor sea, mejor.

## ¿Como se abordo el problema?

Para solucionar el reto se utiliza, el siguiente stack:
- Laravel versión 10.
- Base de Datos Postgresql 13.10
    (En la base de datos se debe considerar el cambio 'charset' => 'latin1', en el archivo config/database.php de pgsql, para no tener inconvenientes con los caracteres).
- nginx:1.23.3

* 1) Se genera el diccionario de datos y se crea la migracion de la tabla consulta_cp, se  define el modelo.
* 2) Se descarga el archivo en formato text, con toda la informacion al 21 de Febrero de 2023, se obtiene del siguiente enlace
https://www.correosdemexico.gob.mx/SSLServicios/ConsultaCP/CodigoPostal_Exportar.aspx contiene los Zip Codes de Mexico.
En el proceso de ETL se contempla dos opciones:
    a.) El uso de seeder a traves del archivo CargaCsvSeeder.php, con el cual se realiza la inyeccion de un archivo csv separado por ; y que previamente se
        ha formateado utilizando el archivo txt. Proceso que tiene un buen performance.
    b.) Existe un metodo que hace uso de stack logstash de elastic, para realizar la carga del archivo csv a postgresql.

Este proceso presenta grado de dificultad debido al tratamiento de grandes cantidades de datos, al utilizar las dos opciones, los resultados son buenos,
pero a mayor cantidad de datos se recomienda utilizar logstash de elasticsearch.

* 3) docker-compose exec app php artisan migrate 
     docker-compose exec app php artisan db:seed --class=CargaCsvSeeder

* 4) Se habilito la ruta replicando el endpoint /api/zip-codes/{zip_code}.
* 5) Se dockerizo la aplicacion API

#Comandos Utilizados en despliegue en Google Cloud
```
comandos para docker working

docker-compose up
docekr-compose down

docker ps -a
docker system prune -a

docker volume ls

docker-compose exec app php artisan config:cache

docker-compose exec app bash

docker volume rm prueba-zip-back_dbdata

pasamos el usuario a root con comando
docker exec -u 0 -ti  iddeimagendocher /bin/bash
ejecutamos composer install

luego habilitamos los permisos de laravel
chmod 777 -R /var/www/storage/logs
chmod 777 -R /var/www/storage/framework

generamos la migracion de tablas
sudo docker-compose exec -u  0 app php artisan migrate

luego realizamos la carga ETL con el seeder
docker-compose exec app php artisan db:seed --class=CargaCsvSeeder
```
## Conclusión

1. El manejo de grandes cantidades de volumenes de informacion, tiene muchas formas de ser trabajadas, laravel 10 y el uso del seeder genera buen rendimiento de carga.
2. El uso de logsth para carga de informacion o ETL, es una buena opcion de manejo de big data.
3. La dockerizacion del stack facilita el despliegue de la API, en google cloud.


##*Nota adicional*: 


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

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
