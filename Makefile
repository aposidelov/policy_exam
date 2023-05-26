include .env

default: up

COMPOSER_ROOT ?= /var/www/html
SYMFONY_ROOT ?= /var/www/html/web

.PHONY: rcli
rcli:
	docker exec -it sym_micro1_rdb redis-cli

.PHONY: console
console:
	docker exec -w $(SYMFONY_ROOT) $(shell docker ps --filter name='$(PROJECT_NAME)_php' --format "{{ .ID }}") php bin/console $(filter-out $@,$(MAKECMDGOALS))

.PHONY: make-entity
make-entity:
	docker exec -it $(shell docker ps --filter name='$(PROJECT_NAME)_php' --format "{{ .ID }}") php web/bin/console make:entity

.PHONY: make-migration
make-migration:
	docker exec -it symfony_example_1_php php web/bin/console make:migration


## ua	:	Start test1 containers.
.PHONY: ua
ua:
	@echo "Test1 content: $(shell docker ps --filter name='$(PROJECT_NAME)_php' --format "{{ .ID }}")"

## up	:	Start up containers.
.PHONY: up
up:
	@echo "Starting up containers for $(PROJECT_NAME)..."
	docker-compose up -d --remove-orphans

## down	:	Stop containers.
.PHONY: down
down: stop

## start	:	Start containers without updating.
.PHONY: start
start:
	@echo "Starting containers for $(PROJECT_NAME) from where you left off..."
	docker-compose start

## stop	:	Stop containers.
.PHONY: stop
stop:
	@echo "Stopping containers for $(PROJECT_NAME)..."
	docker-compose stop

## ps	:	List running containers.
.PHONY: ps
ps:
	@docker ps --filter name='$(PROJECT_NAME)*'

## shell	:	Access `php` container via shell.
.PHONY: ssh
ssh:
	docker exec -it $(shell docker ps --filter name='$(PROJECT_NAME)_php' --format "{{ .ID }}") sh

## composer	:	Executes `composer` command in a specified `COMPOSER_ROOT` directory (default is `/var/www/html`).
##		To use "--flag" arguments include them in quotation marks.
##		For example: make composer "update drupal/core --with-dependencies"
.PHONY: composer
composer:
	docker exec $(shell docker ps --filter name='^/$(PROJECT_NAME)_php' --format "{{ .ID }}") composer --working-dir=$(SYMFONY_ROOT) $(filter-out $@,$(MAKECMDGOALS))

## drush	:	Executes `drush` command in a specified `DRUPAL_ROOT` directory (default is `/var/www/html/web`).
##		To use "--flag" arguments include them in quotation marks.
##		For example: make drush "watchdog:show --type=cron"
.PHONY: drush
drush:
	docker exec $(shell docker ps --filter name='^/$(PROJECT_NAME)_php' --format "{{ .ID }}") drush -r $(DRUPAL_ROOT) $(filter-out $@,$(MAKECMDGOALS))

## logs	:	View containers logs.
##		You can optinally pass an argument with the service name to limit logs
##		logs php	: View `php` container logs.
##		logs nginx php	: View `nginx` and `php` containers logs.
.PHONY: logs
logs:
	@docker-compose logs -f $(filter-out $@,$(MAKECMDGOALS))

# https://stackoverflow.com/a/6273809/1826109
%:
	@:
