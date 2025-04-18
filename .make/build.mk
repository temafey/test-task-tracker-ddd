.PHONY: build-all
build-all: docker-network-create build build-nginx ## build environment and initialize composer and project dependencies

.PHONY: build
build: ## build php
	docker build .docker/php$(DOCKER_PHP_VERSION)-fpm/ \
	--tag $(DOCKER_SERVER_HOST)/$(DOCKER_PROJECT_PATH)/php$(DOCKER_PHP_VERSION)-fpm:$(DOCKER_IMAGE_VERSION) \
		--build-arg DOCKER_IMAGE_VERSION=$(DOCKER_IMAGE_VERSION) \
		--build-arg DOCKER_SERVER_HOST=$(DOCKER_SERVER_HOST) \
		--build-arg DOCKER_PROJECT_PATH=$(DOCKER_PROJECT_PATH) \
		--build-arg DOCKER_PHP_VERSION=$(DOCKER_PHP_VERSION)
		
	docker build .docker/php$(DOCKER_PHP_VERSION)-fpm-composer/ \
	--tag $(DOCKER_SERVER_HOST)/$(DOCKER_PROJECT_PATH)/php$(DOCKER_PHP_VERSION)-fpm-composer:$(DOCKER_IMAGE_VERSION) \
		--build-arg DOCKER_IMAGE_VERSION=$(DOCKER_IMAGE_VERSION) \
		--build-arg DOCKER_SERVER_HOST=$(DOCKER_SERVER_HOST) \
		--build-arg DOCKER_PROJECT_PATH=$(DOCKER_PROJECT_PATH) \
		--build-arg DOCKER_PHP_VERSION=$(DOCKER_PHP_VERSION)

	docker build .docker/php$(DOCKER_PHP_VERSION)-fpm-dev/ \
	--tag $(DOCKER_SERVER_HOST)/$(DOCKER_PROJECT_PATH)/php$(DOCKER_PHP_VERSION)-fpm-dev:$(DOCKER_IMAGE_VERSION) \
		--build-arg DOCKER_IMAGE_VERSION=$(DOCKER_IMAGE_VERSION) \
		--build-arg DOCKER_SERVER_HOST=$(DOCKER_SERVER_HOST) \
		--build-arg DOCKER_PROJECT_PATH=$(DOCKER_PROJECT_PATH) \
		--build-arg DOCKER_PHP_VERSION=$(DOCKER_PHP_VERSION)

.PHONY: build-nginx
build-nginx: ## build nginx
	docker build .docker/nginx \
		--tag $(DOCKER_SERVER_HOST)/$(DOCKER_PROJECT_PATH)/nginx:$(DOCKER_IMAGE_VERSION) \
		-f .docker/nginx/Dockerfile ${DOCKER_BUILD_ARGS} \
		--build-arg DOCKER_IMAGE_VERSION=$(DOCKER_IMAGE_VERSION) \
		--build-arg DOCKER_SERVER_HOST=$(DOCKER_SERVER_HOST) \
		--build-arg DOCKER_PROJECT_PATH=$(DOCKER_PROJECT_PATH)

.PHONY: build-postgres
build-postgres: ## build postgres
	docker build .docker/postgres/ \
		--tag $(DOCKER_SERVER_HOST)/$(DOCKER_PROJECT_PATH)/postgres:$(DOCKER_IMAGE_VERSION) \
		-f .docker/migrations/Dockerfile ${DOCKER_BUILD_ARGS} \
		--build-arg DOCKER_IMAGE_VERSION=$(DOCKER_IMAGE_VERSION) \
		--build-arg DOCKER_SERVER_HOST=$(DOCKER_SERVER_HOST) \
		--build-arg DOCKER_PROJECT_PATH=$(DOCKER_PROJECT_PATH)


