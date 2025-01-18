UID=$(shell id -u)
GID=$(shell id -g)
DOCKER_PHP_SERVICE=php-fpm

start: erase cache-folders build composer-install up npm-install autoload dev optimize sleep migrate work

erase:
		docker-compose down -v

cache-folders:
		mkdir -p ~/.composer && chown ${UID}:${GID} ~/.composer

build:
		docker-compose build && \
		docker-compose pull

composer-install:
		docker-compose run --rm -u ${UID}:${GID} ${DOCKER_PHP_SERVICE} composer install --ignore-platform-req=ext-gd

up:
		docker-compose up -d

stop:
		docker-compose stop

down:
		make stop

bash:
		docker-compose run --rm -u ${UID}:${GID} ${DOCKER_PHP_SERVICE} bash

root:
		docker-compose run --rm -u ${0}:${0} ${DOCKER_PHP_SERVICE} bash

test:
		docker-compose exec --user=${UID} ${DOCKER_PHP_SERVICE} bash -c "vendor/bin/phpunit"

migrate:
		docker-compose exec --user=${UID} ${DOCKER_PHP_SERVICE} bash -c "php artisan migrate:fresh --seed"

fix:
		docker-compose exec --user=${UID} ${DOCKER_PHP_SERVICE} bash -c "php vendor/bin/phpcbf app tests"

dev:
		docker-compose exec --user=${0} ${DOCKER_PHP_SERVICE} bash -c "npm run dev"

watch:
		docker-compose exec --user=${0} ${DOCKER_PHP_SERVICE} bash -c "npm run watch"

npm-install:
		docker-compose exec --user=${0} ${DOCKER_PHP_SERVICE} bash -c "npm install"

optimize:
		docker-compose exec --user=${0} ${DOCKER_PHP_SERVICE} bash -c "php artisan optimize:clear"

autoload:
		docker-compose exec --user=${0} ${DOCKER_PHP_SERVICE} bash -c "composer dump-autoload"

update:
		docker-compose exec --user=${0} ${DOCKER_PHP_SERVICE} bash -c "composer update"

do:
		docker-compose exec --user=${0} ${DOCKER_PHP_SERVICE} bash -c "php artisan make:$(ARGS)"

work:
		docker-compose exec --user=${0} ${DOCKER_PHP_SERVICE} bash -c "php artisan schedule:work"

sleep:
		 echo 'Un momento por favor, estamos preparando el sistema' && sleep 8