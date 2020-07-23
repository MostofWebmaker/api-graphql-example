up: docker-up
down:	docker-down-clear
init:	docker-down-clear	docker-pull docker-build	docker-up
docker-up:
	docker-compose up -d
docker-down:
	docker-compose down --remove-orphans
docker-down-clear:
	docker-compose down -v --remove-orphans
docker-pull:
	docker-compose pull
docker-build:
	docker-compose	build
composer-init:
	docker-compose run --rm saleboard-php-cli composer up
ca-cl:
	docker-compose run --rm saleboard-php-cli  php bin/console ca:cl
saleboard-migrations:
	docker-compose run --rm saleboard-php-cli php bin/console doctrine:migrations:migrate --no-interaction

saleboard-migrations-make:
	docker-compose run --rm saleboard-php-cli php bin/console make:migration

saleboard-fixtures-load:
	docker-compose run --rm saleboard-php-cli php bin/console doctrine:fixtures:load -n
cli:
	docker-compose run --rm saleboard-php-cli php bin/app.php

deploy:	saleboard-migrations saleboard-fixtures-load

run-tests:
	docker-compose run --rm saleboard-php-cli php bin/phpunit