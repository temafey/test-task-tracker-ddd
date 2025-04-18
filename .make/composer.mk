.PHONY: composer-autoscript
composer-autoscript: ## Symfony cache clear and install assets
	$(DCC) run --rm --no-deps $(DOCKER_PHP_CONTAINER_NAME) sh -lc '/app/bin/console -vvv c:c'
	$(DCC) run --rm --no-deps $(DOCKER_PHP_CONTAINER_NAME) sh -lc '/app/bin/console assets:install'

.PHONY: composer-install
composer-install: ## Install project dependencies
	$(DCC) run --rm --no-deps $(DOCKER_PHP_CONTAINER_NAME) sh -lc 'composer install --optimize-autoloader'
	$(DCC) run --rm --no-deps $(DOCKER_PHP_CONTAINER_NAME) sh -lc '/app/bin/console -vvv c:c'
	$(DCC) run --rm --no-deps $(DOCKER_PHP_CONTAINER_NAME) sh -lc '/app/bin/console assets:install'


.PHONY: composer-install-no-dev
composer-install-no-dev: ## Install project dependencies without dev
	$(DCC) run --rm --no-deps $(DOCKER_PHP_CONTAINER_NAME) sh -lc 'composer install --no-dev --optimize-autoloader'
	$(DCC) run --rm --no-deps $(DOCKER_PHP_CONTAINER_NAME) sh -lc '/app/bin/console -vvv c:c'
	$(DCC) run --rm --no-deps $(DOCKER_PHP_CONTAINER_NAME) sh -lc '/app/bin/console assets:install'

.PHONY: composer-update
composer-update: ## Update project dependencies
	$(DCC) run --rm --no-deps $(DOCKER_PHP_CONTAINER_NAME) sh -lc 'composer update --optimize-autoloader --with-all-dependencies'
	$(DCC) run --rm --no-deps $(DOCKER_PHP_CONTAINER_NAME) sh -lc '/app/bin/console -vvv c:c'
	$(DCC) run --rm --no-deps $(DOCKER_PHP_CONTAINER_NAME) sh -lc '/app/bin/console assets:install'

.PHONY: composer-outdated
composer-outdated: ## Show outdated project dependencies
	$(DCC) run --rm --no-deps $(DOCKER_PHP_CONTAINER_NAME) sh -lc 'composer outdated --optimize-autoloader'

.PHONY: composer-validate
composer-validate: ## Validate composer config
	$(DCC) run --rm --no-deps $(DOCKER_PHP_CONTAINER_NAME) sh -lc 'composer validate --no-check-publish --optimize-autoloaders'

.PHONY: composer
composer: ## Execute composer command
	$(DCC) run --rm --no-deps $(DOCKER_PHP_CONTAINER_NAME) sh -lc "composer $(RUN_ARGS) --ignore-platform-reqs"

.PHONY: composer-test
composer-test: ## Run unit and code style tests.
	$(DCC) run --rm --no-deps $(DOCKER_PHP_CONTAINER_NAME) sh -lc "composer test"

.PHONY: composer-fix-style
composer-fix-style: ## Automated attempt to fix code style.
	$(DCC) run --rm --no-deps $(DOCKER_PHP_CONTAINER_NAME) sh -lc "composer fix-style"

.PHONY: composer-preload
composer-preload: ## Generate preload config file
	$(DCC) run --rm --no-deps $(DOCKER_PHP_CONTAINER_NAME) sh -lc 'composer preload'

