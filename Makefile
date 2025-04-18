$(shell (if [ ! -e .env.local ]; then .make/convert-env.sh; fi))
include .env.local
export

ifeq ($(OS),Windows_NT)
    CWD := $(lastword $(dir $(realpath $(MAKEFILE_LIST))))/
else
    CWD := $(abspath $(patsubst %/,%,$(dir $(abspath $(lastword $(MAKEFILE_LIST))))))/
endif

RUN_ARGS = $(filter-out $@,$(MAKECMDGOALS))
DCC = docker-compose -f $(CWD)docker-compose.yml --project-directory $(CWD)

include .make/utils.mk
include .make/build.mk
include .make/composer.mk
include .make/migrations.mk

.PHONY: install
install: fix-permission erase build-all composer-install start-all setup## clean current environment, recreate dependencies and spin up again

.PHONY: install-lite
install-lite: build setup start

.PHONY: start
start: ##up-services ## spin up environment
	$(DCC) up -d

.PHONY: stop
stop: ## stop environment
	$(DCC) stop

.PHONY: remove
remove: ## remove project docker containers
	$(DCC) rm -v -f

.PHONY: erase
erase: stop remove docker-remove-volumes ## stop and delete containers, clean volumes

.PHONY: setup
setup: setup-db setup-enqueue ## build environment and initialize composer and project dependencies

.PHONY: clear-events
clear-events: ## setup enqueue
	$(DCC) run --rm $(DOCKER_PHP_CONTAINER_NAME) sh -lc './bin/console cleaner:clear db'

.PHONY: console
console: ## execute symfony console command
	$(DCC) run --rm --no-deps $(DOCKER_PHP_CONTAINER_NAME) sh -lc "./bin/console -vvv $(RUN_ARGS)"

.PHONY: lint-container
lint-container: ## checks that the arguments injected into services match their type declarations
	$(DCC) run --rm --no-deps $(DOCKER_PHP_CONTAINER_NAME) sh -lc "./bin/console lint:container"

.PHONY: php-test
php-test: ## PHP shell without deps
	$(DCC) run --rm --no-deps $(DOCKER_PHP_CONTAINER_NAME) sh -l

.PHONY: php-shell
php-shell: ## PHP shell
	$(DCC) run --rm $(DOCKER_PHP_CONTAINER_NAME) sh -l

.PHONY: php-shell-no-deps
php-shell-no-deps: ## PHP shell without deps
	$(DCC) run --rm --no-deps $(DOCKER_PHP_CONTAINER_NAME) sh -l

.PHONY: nginx-shell
nginx-shell: ## nginx shell
	$(DCC) run --rm  --no-deps test-nginx sh -l

.PHONY: clean
clean: ## Clear build vendor report folders
	rm -rf build/ vendor/ var/
