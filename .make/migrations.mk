.PHONY: setup-db
setup-db: ## recreate database
	$(DCC) run --rm $(DOCKER_PHP_CONTAINER_NAME) sh -lc './bin/console d:d:d --force --if-exists'
	$(DCC) run --rm $(DOCKER_PHP_CONTAINER_NAME) sh -lc './bin/console d:d:c'
	$(DCC) run --rm $(DOCKER_PHP_CONTAINER_NAME) sh -lc './bin/console d:m:m -n'

.PHONY: schema-validate
schema-validate: ## validate database schema
	$(DCC) run --rm $(DOCKER_PHP_CONTAINER_NAME) sh -lc "./bin/console d:s:v"

.PHONY: migration-generate
migration-generate: ## generate new database migration
	$(DCC) run --rm $(DOCKER_PHP_CONTAINER_NAME) sh -lc './bin/console d:m:g'

.PHONY: migration-migrate
migration-migrate: ## execute a migration to a specified version or the latest available version
	$(DCC) run --rm $(DOCKER_PHP_CONTAINER_NAME) sh -lc './bin/console d:m:m -n'

.PHONY: migration-diff
migration-diff: ## generate a migration by comparing your current database to your mapping information.
	$(DCC) run --rm $(DOCKER_PHP_CONTAINER_NAME) sh -lc './bin/console d:m:diff -n'

.PHONY: setup-enqueue
setup-enqueue: ## setup enqueue
	$(DCC) run --rm $(DOCKER_PHP_CONTAINER_NAME) sh -lc './bin/console enqueue:setup-broker -c task'
	$(DCC) run --rm $(DOCKER_PHP_CONTAINER_NAME) sh -lc './bin/console enqueue:setup-broker -c event'
	$(DCC) run --rm $(DOCKER_PHP_CONTAINER_NAME) sh -lc './bin/console enqueue:setup-broker -c queueevent'
	$(DCC) run --rm $(DOCKER_PHP_CONTAINER_NAME) sh -lc './bin/console enqueue:setup-broker -c taskevent'
	$(DCC) run --rm $(DOCKER_PHP_CONTAINER_NAME) sh -lc './bin/console enqueue:setup-broker -c global.task-tracker'

database-setup-db:
	$(DCC) exec ${APP_DATABASE_HOST} sh -c "if PGPASSWORD=$(POSTGRES_PASSWORD); psql -U $(POSTGRES_USER) postgres -XtAc '\l' | grep $(POSTGRES_DB); then echo DB $(POSTGRES_DB) already exists; else PGPASSWORD=$(POSTGRES_PASSWORD) createdb -U $(POSTGRES_USER) postgres --echo $(POSTGRES_DB); fi"

database-shell: ## POSTGRES console
	$(DCC) exec ${APP_DATABASE_HOST} psql -U ${APP_DATABASE_LOGIN} ${APP_DATABASE_NAME}

database-console: ## POSTGRES shell
	$(DCC) exec ${APP_DATABASE_HOST} psql -U ${APP_DATABASE_LOGIN} ${APP_DATABASE_NAME} -XtAc "\l"
