make.DEFAULT_GOAL := help

DOCKER_COMPOSE := docker-compose --file docker/docker-compose.yml -p kamikaze
# PHP_UNIT := ./vendor/symfony/phpunit-bridge/bin/simple-phpunit -c app --exclude=communications
PHP_UNIT := bin/phpunit
CACHE-ENV := "dev"
FIXTURES-ENV := "dev"

.PHONY: help
help:
	@echo ""
	@echo "Available tasks:"
	@echo "    init                    [HOST] Launch and setup all instances"
	@echo "    start                   [HOST] Run Docker up detached"
	@echo "    stop                    [HOST] Stop docker-compose instances down"
	@echo "    force-stop              [HOST] Stop all docker instances"
	@echo "    kill                    [HOST] docker-compose kill"
	@echo "    recreate                [HOST] Docker compose up with build option"
	@echo "    recreate-force          [HOST] Docker compose up with build and force option"
	@echo "    restart                 [HOST] Make Start + Stop"
	@echo "    force-restart           [HOST] Make force-stop start"
	@echo "    composer-dump-autoload  [DOCK] Reset composer autoload info"
	@echo "    composer-install        [DOCK] Run composer install inside container"
	@echo "    composer-update         [DOCK] Run composer update inside container"
	@echo "    composer-require        [DOCK] Run composer require inside container"
	@echo "    bash-php-fpm            [DOCK] Access fpm container shell"
	@echo "    bash-nginx              [DOCK] Access nginx container shell"
	@echo "    bash-db                 [DOCK] Access database container shell"
	@echo "    cache-clear             [DOCK] Run Symfony clear cache command"
	@echo "    db-fixtures-load        [DOCK] Run Symfony fixtures load"
	@echo "    db-schema-load          [DOCK] Run Symfony schema load"
	@echo "    db-schema-dump          [DOCK] Run Symfony schema dump"
	@echo "    db-generate-migration   [DOCK] Run Symfony generate migration"
	@echo "    db-migration-migrate    [DOCK] Run Symfony execute migrations"
	@echo "    phpcs                   [DOCK] Run phpcs in order to detect violations of a defined coding standard"
	@echo "    test                    [DOCK] Execute all tests generating fixtures"
	@echo "    test-phpunit            [DOCK] Run all phpunit test suites"
	@echo "    fix-permissions         [DOCK] Change permissions for var/log and var/cache folders"
	@echo ""

.PHONY: init
init:
	make start
	make composer-install
	sleep 4
	make db-schema-load
	make db-fixtures-load-append
	# make fix-permissions

.PHONY: start
start:
	$(DOCKER_COMPOSE) up -d --remove-orphans
	$(DOCKER_COMPOSE) run docker-php-fpm chmod -R 777 bin/console

.PHONY: stop
stop:
	$(DOCKER_COMPOSE) down

.PHONY: force-stop
force-stop:
	docker stop $(docker ps -a -q)

.PHONY: kill
kill:
	$(DOCKER_COMPOSE) kill

.PHONY: recreate
recreate:
	$(DOCKER_COMPOSE) up -d --build

.PHONY: recreate-force
recreate-force:
	$(DOCKER_COMPOSE) up -d --build --force-recreate

.PHONY: restart
restart: stop start

.PHONY: force-restart
force-restart: force-stop start

#PHP - Composer
.PHONY: composer-dump-autoload
composer-dump-autoload:
	$(DOCKER_COMPOSE) run --rm -u $(UID):$(GID) docker-php-fpm composer dump-autoload --classmap-authoritative

.PHONY: composer-install
composer-install:
	$(DOCKER_COMPOSE) run --rm -u $(UID):$(GID) docker-php-fpm composer install -vvv

.PHONY: composer-update
composer-update:
	$(DOCKER_COMPOSE) run -u $(UID):$(GID) docker-php-fpm composer update -d ./api

.PHONY: composer-require
composer-require:
	$(DOCKER_COMPOSE) run --rm -u $(UID):$(GID) docker-php-fpm composer require

.PHONY: make-fixture
make-fixture:
	$(DOCKER_COMPOSE) run --rm -u $(UID):$(GID) docker-php-fpm bin/console make:fixtures

.PHONY: bash-php-fpm
bash-php-fpm:
	$(DOCKER_COMPOSE) exec docker-php-fpm bash

.PHONY: bash-nginx
bash-nginx:
	$(DOCKER_COMPOSE) exec docker-nginx bash

.PHONY: bash-db
bash-db:
	$(DOCKER_COMPOSE) exec docker-db bash

.PHONY: cache-clear
cache-clear:
	$(DOCKER_COMPOSE) exec docker-php-fpm bin/console cache:clear --env=$(CACHE-ENV)

.PHONY: db-fixtures-load
db-fixtures-load:
	$(DOCKER_COMPOSE) exec docker-php-fpm bin/console doctrine:fixtures:load

.PHONY: db-fixtures-load-append
db-fixtures-load-append:
	$(DOCKER_COMPOSE) exec docker-php-fpm bin/console doctrine:fixtures:load --append --env=$(FIXTURES-ENV)

.PHONY: db-schema-load
db-schema-load:
	$(DOCKER_COMPOSE) exec docker-php-fpm bin/console doctrine:schema:update --force

.PHONY: db-schema-dump
db-schema-dump:
	$(DOCKER_COMPOSE) exec docker-php-fpm bin/console doctrine:schema:update --dump-sql

.PHONY: db-generate-migration
db-generate-migration:
	$(DOCKER_COMPOSE) exec docker-php-fpm bin/console doctrine:migration:diff

.PHONY: db-migration-migrate
db-migration-migrate:
	$(DOCKER_COMPOSE) exec docker-php-fpm bin/console doctrine:migration:migrate

.PHONY: phpcs
phpcs:
	$(DOCKER_COMPOSE) exec docker-php-fpm ./vendor/bin/phpcs ./src | more

.PHONY: test
test:
	make start
	make composer-dump-autoload
	make cache-clear CACHE-ENV=test
	make test-prepare-db
	make test-phpunit

.PHONY: fix-permissions
fix-permissions:
	$(DOCKER_COMPOSE) run docker-php-fpm chmod -R 777 var/log var/cache

test-prepare-db:
	$(DOCKER_COMPOSE) run --rm -u $(UID):$(GID) docker-php-fpm php bin/console --env=test doctrine:database:drop --if-exists --force
	$(DOCKER_COMPOSE) run --rm -u $(UID):$(GID) docker-php-fpm php bin/console --env=test doctrine:database:create 
	$(DOCKER_COMPOSE) run --rm -u $(UID):$(GID) docker-php-fpm php bin/console --env=test doctrine:schema:create -q
	make db-fixtures-load-append  FIXTURES-ENV=test

.PHONY: test-phpunit
test-phpunit:
	$(DOCKER_COMPOSE) run docker-php-fpm chmod -R 777 bin/phpunit
	$(DOCKER_COMPOSE) run --rm -u $(UID):$(GID) docker-php-fpm $(PHP_UNIT) $(TEST_DIR) $(GROUP) --stop-on-error --stop-on-failure
	

.PHONY: compatibility-leads
compatibility-leads:
	$(DOCKER_COMPOSE) exec docker-php-fpm bin/console app:compatibility-leads

