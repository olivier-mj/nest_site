isDocker := $(shell docker info > /dev/null 2>&1 && echo 1)
domain := "donpadre.fr"
server := "donpadre.fr"
user := $(shell id -u)
group := $(shell id -g)
ifeq ($(isDocker), 1)
	dc := USER_ID=$(user) GROUP_ID=$(group) docker-compose
	de := $(dc) exec 
	dr := $(dc) run --rm
	sy := $(de) php bin/console
	drtest := $(dc) -f docker-compose.test.yml run --rm
	node := $(dr) node
	php := $(dr) --no-deps php
else
	sy := php bin/console
	node := node
	php := php
endif


.DEFAULT_GOAL := help
.PHONY: help
help: ## Affiche cette aide
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

du: ## docker-compose up and build
	$(dc) up -d --build
dd: ## docker-compose down
	$(dc) down --remove-orphans
dr: ## docker-compose down and up
	$(dc) down --remove-orphans
	$(dc) up -d --build
dl: ## docker-compose logs
	$(dc) logs --follow --tail="30"


.PHONY:  prod
# prod: ## build and analyse project for production
# 	$(MAKE)	analyse
# 	$(MAKE) prepare-test
# 	$(MAKE)	tests

prod:
	$(MAKE) rc;
	$(MAKE) cc;
	APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear;
	APP_ENV=prod  yarn run build


.PHONY: translations
translations: ## make translation
# $(sy) translation:update --force fr
	$(sy) translation:update 
indexer: ## index all articles
	$(sy) app:index

analyze: ## run yarn audi, valid composer.json & doctrine scheme
#	npm audit
	composer valid
	$(sy) doctrine:schema:valid --skip-sync
	$(sy) vendor/squizlabs/php_codesniffer/bin/phpcs
	$(MAKE) stan
#	php vendor/bin/phpstan analys

stan: ##run phpstan
	$(de) php   vendor/bin/phpstan analyse

unit:
	APP_ENV=test $(de) php bin/phpunit

# .PHONY: tt
# tt: vendor/autoload.php ## Lance le watcher phpunit
# 	# $(de) php bin/console cache:clear --env=test
# 	$(de) php vendor/bin/phpunit-watcher watch --filter="nothing"


create:
	$(MAKE) create-dev
	$(MAKE) create-test

create-dev: ## Create database
	$(sy) doctrine:database:create --env=dev

create-test: ## Create database
	$(sy) doctrine:database:create --env=test

migration: ## create migration
	$(sy)  make:migration

drop: ## drop database 
	$(sy)  doctrine:database:drop --if-exists  --force 
	$(sy)  doctrine:database:create

migrate: ## migrate migration in database
	$(sy)  doctrine:migrations:migrate
	dock

fixtures-dev: ## load fixtures for dev
	$(sy)  doctrine:fixtures:load -n --env=dev

fixtures-test: ## load fixtures for tests
	$(sy)  doctrine:fixtures:load -n --env=test

database-dev: ## drop and re-create database
	$(sy)  doctrine:database:drop --if-exists  --force --env=dev
	$(sy)  doctrine:database:create --env=dev
	$(sy)  doctrine:schema:update -f --env=dev

database-test: ## drop and re-create database
	$(sy) doctrine:database:drop --if-exists  --force --env=test
	$(sy) doctrine:database:create --env=test
	$(sy) doctrine:schema:update -f --env=test

prepare-dev: ## prepare project for dev environment
	$(sy) cache:clear --env=dev
	$(MAKE) database-dev
	$(MAKE) fixtures-dev

prepare-test: ##prepare project for test environment
	$(sy) cache:clear --env=test
	$(MAKE) database-test
	$(MAKE) fixtures-test

prepare-build:
	make database-test
	make fixtures-test
	npm run dev

install: ## install composer and yarn packages
	composer install
	yarn install

valid: ##  force update database
	$(sy) doctrine:schema:update --force

regen: ##  regenerate entities
	$(sy) make:entity --regenerate --overwrite

rollback:
	$(sy) doctrine:migrations:rollup

lint: ## run phpstan phpcs lint:twig
	php vendor/bin/phpcs -w
	$(MAKE) stan
	$(sy)  lint:twig
format: ## Format code
#	 php vendor/bin/phpcs -w
	$(sy) vendor/bin/phpcbf


npx: ## format js with prettier
	npx prettier-standard --lint --changed "assets/**/*.{js,css,jsx}"


start: ## run symfony server
	symfony server:start -d 

stop: ## stop symfony server
	symfony server:stop

status:  ## status symfony server
	symfony server:status

restart: ## restart dev server
	symfony server:stop
	symfony server:start -d 

ca-i:
	symfony server:ca:install
ca-u:
	symfony server:ca:uninstall

env: ## make env file for dev and test environment
	cp .env.dist .env.dev.local
	cp .env.dist .env.test.local

.PHONY: log
log: ## logs symfony server
	symfony server:log

update:
	composer update
	yarn upgrade

cc: # clear cache
	$(sy) cache:clear --env=dev --no-warmup
	$(sy) cache:clear --env=test --no-warmup

su: ## update symfony
	$(de)  php symfony

security-check: vendor/autoload.php ## Check pour les vulnérabilités des dependencies
	USER_ID=$(user) GROUP_ID=$(group)  $(de) php local-php-security-checker --path=/var/www

debug:
	$(dc) config

db: ## console mariadb
	$(de) database  mariadb -unest -pnest

dump: # dump database
	# $(de) database mysqldump -unest -pnest nest  | gzip > dump_nest_database.sql.gz
	$(de) database mysqldump -unest -pnest nest  > database_dump.sql

bash:  ## open bash console in container	
	$(de) php bash

rc: ## Redis  flush all database
	$(de) redis redis-cli flushall

diff: #migration diff
	$(sy)  doctrine:migration:diff 


watch:
	yarn run watch --hot

build:
	yarn run build

reload: 
	yarn encore dev-server  --port 9000 --live-reload


view: 
	yarn run tailwind