# dev-up:
# 	docker network create app
# 	docker run -d --name saleboard-php-fpm -v ${PWD}/saleboard:/app --network=app saleboard-php-fpm
# 	docker run -d --name saleboard-nginx -v ${PWD}/saleboard:/app -p 8080:80 --network=app saleboard-nginx
# # 	docker run -d --name saleboard-apache -v ${PWD}/saleboard:/app -p 8080:80 saleboard-apache
# dev-down:
# 	docker stop saleboard-nginx
# 	docker stop saleboard-php-fpm
# 	docker rm saleboard-nginx
# 	docker rm saleboard-php-fpm
# 	docker network remove app
up: docker-up
down:	docker-down-clear
# добавить saleboard-migrations
init:	docker-down-clear	docker-pull docker-build	docker-up
docker-up:
	docker-compose up -d
docker-down:
	docker-compose down --remove-orphans
docker-down-clear:
	docker-compose down -v --remove-orphans
docker-pull:
	docker-compose pull
# dev-build:
# 	docker build --file=saleboard/docker/dev/php-cli.docker -t saleboard-php-cli saleboard/docker/dev
# 	docker build --file=saleboard/docker/dev/apache.docker --tag saleboard-apache saleboard/docker/dev
docker-build:
	docker-compose	build
composer-init:
	docker-compose run --rm saleboard-php-cli composer up
ca-cl:
	docker-compose run --rm saleboard-php-cli  php bin/console ca:cl
# добавить mysql-wait:
# until docker-compose exec -T saleboard-mysql mysql_isready --timeout=0 --dbname=saleboard ; do sleep 1 ; done
# 	until docker-compose exec -T saleboard-mysql while ! mysqladmin ping -h"3306" --silent; do sleep 1; done
saleboard-migrations:
	docker-compose run --rm saleboard-php-cli php bin/console doctrine:migrations:migrate --no-interaction

saleboard-migrations-make:
	docker-compose run --rm saleboard-php-cli php bin/console make:migration

saleboard-fixtures-load:
	docker-compose run --rm saleboard-php-cli php bin/console doctrine:fixtures:load -n
# prod-build:
# 	docker build --file=saleboard/docker/prod/php-cli.docker --tag saleboard-php-cli saleboard
# 	docker build --file=saleboard/docker/prod/apache.docker --tag saleboard-apache saleboard
# dev-cli:
# 	docker run —rm -v ${PWD}/saleboard:/app saleboard-php-cli php bin/app.php
# prod-cli:
# 	docker run —rm manager-php-cli php bin/app.php
cli:
	docker-compose run --rm saleboard-php-cli php bin/app.php

deploy:	saleboard-migrations saleboard-fixtures-load

run-tests:
	docker-compose run --rm saleboard-php-cli php bin/phpunit

build-prod:
	docker build --file=saleboard/docker/prod/nginx.docker --tag ${REGISTRY_ADDRESS}/saleboard-nginx:${IMAGE_TAG} saleboard
	docker build --file=saleboard/docker/prod/php-fpm.docker --tag ${REGISTRY_ADDRESS}/saleboard-php-fpm:${IMAGE_TAG} saleboard
	docker build --file=saleboard/docker/prod/php-cli.docker --tag ${REGISTRY_ADDRESS}/saleboard-php-cli:${IMAGE_TAG} saleboard

push-prod:
	docker push ${REGISTRY_ADDRESS}/saleboard-nginx:${IMAGE_TAG}
	docker push ${REGISTRY_ADDRESS}/saleboard-php-fpm:${IMAGE_TAG}
	docker push ${REGISTRY_ADDRESS}/saleboard-php-cli:${IMAGE_TAG}

deploy-prod:
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'rm -rf docker-compose.yml .env'
	scp -o StrictHostKeyChecking=no -P ${PRODUCTION_PORT} docker-compose-prod.yml ${PRODUCTION_HOST}:docker-compose.yml
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "REGISTRY_ADDRESS=${REGISTRY_ADDRESS}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "IMAGE_TAG=${IMAGE_TAG}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "MANAGER_APP_SECRET=${MANAGER_APP_SECRET}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "MANAGER_DB_PASSWORD=${MANAGER_DB_PASSWORD}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'docker-compose pull'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'docker-compose up --build -d'

