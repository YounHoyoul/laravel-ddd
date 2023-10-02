<h1 align="center">
  Laravel 10 DDD with Inertia.js & Octane & Sail
</h1>

<p align="center">
    <a href="https://laravel.com/"><img src="https://img.shields.io/badge/Laravel-10-FF2D20.svg?style=flat&logo=laravel" alt="Laravel 10"/></a>
    <a href="https://www.php.net/"><img src="https://img.shields.io/badge/PHP-8.2-777BB4.svg?style=flat&logo=php" alt="PHP 8.2"/></a>
    <a href="https://github.com/orphail/laravel-ddd/actions"><img src="https://github.com/younhoyoul/laravel-ddd/actions/workflows/laravel-tests.yml/badge.svg" alt="GithubActions"/></a>
</p>

## Base project
https://github.com/Orphail/laravel-ddd

## First Step 
For launch, run Laravel Sail first
```
docker compose build --no-cache
docker compose up -d
docker compose exec laravel.test bash
```
In the laravel.test container,
```
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run build
```
For new domains, use this command: `php artisan make:domain {Bounded Context} {Domain}` (e.g. `php artisan make:domain Blog Post`)
## Modification
### Laravel Sail
### Laravel Octane with Swoole
### PHP xDebug
In order to enable xDebug with Laravel Sail
1. add below in /docker/8.2/php.ini
```
[XDebug]
zend_extension = xdebug.so
xdebug.mode = debug
xdebug.start_with_request = yes
xdebug.discover_client_host = true
xdebug.idekey = VSC
xdebug.client_host = host.docker.internal
xdebug.client_port = 9003
```
3. update Docker file
```
...
ARG XDEBUG
...
RUN if [ ${XDEBUG}] ; then \
    apt-get install -y php-xdebug; \
fi;
...
```
4. update supervisord.conf
The Swoole server doesn't support xDebug at the moment. So, in order to use xDebug, we need to use php default server.
```
[program:octane]
command=/usr/bin/php -d variables_order=EGPCS /var/www/html/artisan octane:start --server=swoole --host=0.0.0.0 --port=80 --watch
user=sail
environment=LARAVEL_SAIL="1"
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0

[program:php-xdebug]
command=/usr/bin/php -d variables_order=EGPCS /var/www/html/artisan serve --host=0.0.0.0 --port=8000
user=sail
environment=LARAVEL_SAIL="1"
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0
```
5. rebuild docker with `docker compose build --no-cache`
6. add launch.json
```
{
    // Use IntelliSense to learn about possible attributes.
    // Hover to view descriptions of existing attributes.
    // For more information, visit: https://go.microsoft.com/fwlink/?linkid=830387
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for XDebug on Docker App",
            "type": "php",
            "request": "launch",
            "port": 9003,
            "pathMappings": {
                "/var/www/html": "${workspaceFolder}"
            },
            "hostname": "localhost",
            "xdebugSettings": {
                "max_data": 65535,
                "show_hidden": 1,
                "max_children": 100,
                "max_depth": 5
            }
        }
    ]
}
```
7. update .env
```
SAIL_XDEBUG_MODE=develop,debug
```
8. run docker 
```
docker compose up -d
```
9. debug code
Debug with http://localhost:8000/
