# match-app

composer run check-code
vendor/bin/php-cs-fixer fix
vendor/bin/phpstan analyse src


php bin/console make:entity --regenerate

## CZ
Projekt můžeme spustit pomocí příkazu `make start` v terminálu ve složce, kde se nachází soubor `Makefile` v kterem jsou taky podprikazy (v našem případě ve složce `match-app`).

Je nutné mít volné porty `80`, `8080` a `3306` (pokud nejsou volné, je třeba je uvolnit nebo upravit podle potřeby v souboru `docker-compose.yml`).

Pokud vše proběhne v pořádku, uvidíme následující v terminálu:

```bash
Creating match-app_db_1 ... done
Creating match-app_php_1     ... done
Creating match-app_adminer_1 ... done
Creating match-app_web_1     ... done
docker-compose exec php su --command="composer -n install --prefer-dist" www-data
Installing dependencies from lock file (including require-dev)
Verifying lock file contents can be installed on current platform.
Nothing to install, update or remove
Generating autoload files
128 packages you are using are looking for funding.
Use the `composer fund` command to find out more!

Run composer recipes at any time to see the status of your Symfony recipes.

Executing script cache:clear [OK]
Executing script assets:install public [OK]
Executing script importmap:install [OK]

docker-compose exec -T php su --command="echo 'yes' | bin/console doctrine:migration:migrate" www-data

 WARNING! You are about to execute a migration in database "mydb" that could result in schema changes and data loss. Are you sure you wish to continue? (yes/no) [yes]:
 > 
[notice] Migrating up to DoctrineMigrations\Version20240909082703
[warning] Migration DoctrineMigrations\Version20240908075708 was executed but did not result in any SQL statements.
[notice] finished in 793.3ms, used 22M memory, 4 migrations executed, 5 sql queries
 [OK] Successfully migrated to version: DoctrineMigrations\Version20240909082703


docker-compose exec php su --command="bin/console app:generate-yeti-data" www-data
Yeti data generated successfully!
The web server can be accessed at http://localhost:80.
Adminer is available at http://localhost:8080.
Here are the login credentials for Adminer:
System: MySQL
Server: db
Username: root
Password: toor
Database: mydb
docker exec -it --user www-data match-app_php_1 bash
www-data@61a47a438147:/project$ 
```

Pomocí příkazu ```bin/console app:generate-yeti-data``` můžeme vytvořit falešná data. 
Prvních 10 záznamů Yetiho by se mělo vytvořit už při spuštění příkazu ```make start```.

Pokud se objeví chyba, je třeba zjistit, co ji způsobuje, a odstranit ji.

Webový server je dostupný na adrese [http://localhost:80](http://localhost:80).
Adminer je dostupný na adrese [http://localhost:8080](http://localhost:8080).
Přihlašovací údaje pro Adminer jsou:
- Systém: MySQL
- Server: db
- Uživatelské jméno: root
- Heslo: toor
- Databáze: mydb




--------------------------------------------------------------------------------------------

## EN
We can start the project using the make start command in the terminal from the folder where the Makefile is located (in our case, the match-app folder).

You need to have free ports 80, 8080, and 3306 (if these ports are not free, you’ll need to free them up or adjust them as needed in the docker-compose.yml file).

If everything runs smoothly, we will see the following in the terminal:

```bash
Creating match-app_db_1 ... done
Creating match-app_php_1     ... done
Creating match-app_adminer_1 ... done
Creating match-app_web_1     ... done
docker-compose exec php su --command="composer -n install --prefer-dist" www-data
Installing dependencies from lock file (including require-dev)
Verifying lock file contents can be installed on current platform.
Nothing to install, update or remove
Generating autoload files
128 packages you are using are looking for funding.
Use the `composer fund` command to find out more!

Run composer recipes at any time to see the status of your Symfony recipes.

Executing script cache:clear [OK]
Executing script assets:install public [OK]
Executing script importmap:install [OK]

docker-compose exec -T php su --command="echo 'yes' | bin/console doctrine:migration:migrate" www-data

 WARNING! You are about to execute a migration in database "mydb" that could result in schema changes and data loss. Are you sure you wish to continue? (yes/no) [yes]:
 > 
[notice] Migrating up to DoctrineMigrations\Version20240909082703
[warning] Migration DoctrineMigrations\Version20240908075708 was executed but did not result in any SQL statements.
[notice] finished in 793.3ms, used 22M memory, 4 migrations executed, 5 sql queries
 [OK] Successfully migrated to version: DoctrineMigrations\Version20240909082703


docker-compose exec php su --command="bin/console app:generate-yeti-data" www-data
Yeti data generated successfully!
The web server can be accessed at http://localhost:80.
Adminer is available at http://localhost:8080.
Here are the login credentials for Adminer:
System: MySQL
Server: db
Username: root
Password: toor
Database: mydb
docker exec -it --user www-data match-app_php_1 bash
www-data@61a47a438147:/project$ 
```

Using the command ```bin/console app:generate-yeti-data```, we can generate fake data. 
The first 10 Yeti records should be created when running the command ```make start```.

If an error occurs, you will need to identify and fix the cause.

The web server is accessible at http://localhost:80. Adminer is accessible at http://localhost:8080. The login credentials for Adminer are:

- System: MySQL
- Server: db
- Username: root
- Password: toor
- Database: mydb