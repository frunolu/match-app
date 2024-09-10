up:
	docker-compose up -d --build --force-recreate --remove-orphans

down:
	docker-compose down --volumes --remove-orphans

composer-install:
	docker-compose exec php su --command="composer -n install --prefer-dist" www-data

cache-clean:
	git clean -fdX project/var/cache/*

start: down up composer-install yeti-data php

php:
	docker exec -it --user www-data match-app_php_1 bash

yeti-data:
	docker-compose exec php su --command="bin/console app:generate-yeti-data" www-data

