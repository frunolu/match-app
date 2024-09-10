up:
	docker-compose up -d --build --force-recreate --remove-orphans

down:
	docker-compose down --volumes --remove-orphans

composer-install:
	docker-compose exec php su --command="composer -n install --prefer-dist" www-data

cache-clean:
	git clean -fdX project/var/cache/*

php:
	docker exec -it --user www-data match-app_php_1 bash

yeti-data:
	docker-compose exec php su --command="bin/console app:generate-yeti-data" www-data

migration:
	docker-compose exec -T php su --command="echo 'yes' | bin/console doctrine:migration:migrate" www-data


start: down up composer-install migration yeti-data gap gap info credentials gap gap php

info:
	@echo "------------------------------------------------------"
	@echo "The web server can be accessed at http://localhost:80."
	@echo "Adminer is available at http://localhost:8080."
	@echo ""

gap:
	@echo ""

credentials:
	@echo "Here are the login credentials for Adminer:"
	@echo "System: MySQL"
	@echo "Server: db"
	@echo "Username: root"
	@echo "Password: toor"
	@echo "Database: mydb"
	@echo "------------------------------------------------------"