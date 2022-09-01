# Enable buildkit for docker and docker-compose by default for every environment.
# For specific environments (e.g. MacBook with Apple Silicon M1 CPU) it should be turned off to work stable
# - this can be done in the make/.env file
COMPOSE_DOCKER_CLI_BUILD?=1
DOCKER_BUILDKIT?=1

export COMPOSE_DOCKER_CLI_BUILD
export DOCKER_BUILDKIT

# Container names. Must match the names used in the docker-composer.yml files
DOCKER_SERVICE_NAME_NGINX:=nginx
DOCKER_SERVICE_NAME_REDIS:=redis
DOCKER_SERVICE_NAME_PHP_BASE:=php-base
DOCKER_SERVICE_NAME_PHP_FPM:=php-fpm
DOCKER_SERVICE_NAME_APPLICATION:=application

DOCKER_DIR:=./docker
DOCKER_ENV_FILE:=$(DOCKER_DIR)/.env
DOCKER_COMPOSE_DIR:=$(DOCKER_DIR)
DOCKER_COMPOSE_FILE:=$(DOCKER_COMPOSE_DIR)/docker-compose.yml
DOCKER_COMPOSE_PROJECT_NAME?=app-project

# we need a couple of environment variables for docker-compose so we define a make-variable that we can
# then reference later in the Makefile without having to repeat all the environment variables
DOCKER_COMPOSE_COMMAND:=ENV=$(ENV) \
 DOCKER_REGISTRY=$(DOCKER_REGISTRY) \
 DOCKER_NAMESPACE=$(DOCKER_NAMESPACE) \
 APP_USER_NAME=$(APP_USER_NAME) \
 APP_GROUP_NAME=$(APP_GROUP_NAME) \
 docker-compose -p $(DOCKER_COMPOSE_PROJECT_NAME) --env-file $(DOCKER_ENV_FILE)

DOCKER_COMPOSE:=$(DOCKER_COMPOSE_COMMAND) -f $(DOCKER_COMPOSE_FILE) -f $(DOCKER_COMPOSE_FILE)

EXECUTE_IN_ANY_CONTAINER?=
EXECUTE_IN_APPLICATION_CONTAINER?=

DOCKER_SERVICE_NAME?=

EXECUTE_IN_CONTAINER?=
ifndef EXECUTE_IN_CONTAINER
	# check if 'make' is executed in a docker container, see https://stackoverflow.com/a/25518538/413531
	ifeq ("$(wildcard /dockerenv)","")
		EXECUTE_IN_CONTAINER=true
	endif
endif
ifeq ($(EXECUTE_IN_CONTAINER),true)
	EXECUTE_IN_ANY_CONTAINER:=$(DOCKER_COMPOSE) exec -T --user $(APP_USER_NAME) $(DOCKER_SERVICE_NAME)
	EXECUTE_IN_APPLICATION_CONTAINER:=$(DOCKER_COMPOSE) exec -T --user $(APP_USER_NAME) $(DOCKER_SERVICE_NAME_APPLICATION)
	EXECUTE_IN_REDIS_CONTAINER:=$(DOCKER_COMPOSE) exec $(DOCKER_SERVICE_NAME_REDIS)
endif

##@ [Docker]

.PHONY: docker-clean
docker-clean: ## Remove the .env file for docker
	@rm -f $(DOCKER_ENV_FILE)

.PHONY: docker-init
docker-init: ## Create the .env file
	@cp $(DOCKER_ENV_FILE).example $(DOCKER_ENV_FILE)
	@echo "Please update your docker/.env file with your application settings"

.PHONY: validate-docker-variables
validate-docker-variables: docker/.env
	@$(if $(DOCKER_REGISTRY),,$(error DOCKER_REGISTRY is undefined - Did you run make-init?))
	@$(if $(DOCKER_NAMESPACE),,$(error DOCKER_NAMESPACE is undefined - Did you run make-init?))
	@$(if $(APP_USER_NAME),,$(error APP_USER_NAME is undefined - Did you run make-init?))
	@$(if $(APP_GROUP_NAME),,$(error APP_GROUP_NAME is undefined - Did you run make-init?))

docker/.env:
	@cp $(DOCKER_ENV_FILE).example $(DOCKER_ENV_FILE)

.PHONY:docker-build
docker-build-image: validate-docker-variables ## Build all docker images OR a specific image by providing the service name via: make docker-build DOCKER_SERVICE_NAME=<service>
	$(DOCKER_COMPOSE) build $(DOCKER_SERVICE_NAME)

.PHONY: docker-up
docker-up: validate-docker-variables ## Create and start all docker containers. To create/start only a specific container, use make docker-up DOCKER_SERVICE_NAME=<service>
	@$(DOCKER_COMPOSE) up -d $(DOCKER_SERVICE_NAME)

.PHONY: docker-down
docker-down: validate-docker-variables ## Stop and remove all docker containers. To stop only a specific container, use make docker-down DOCKER_SERVICE_NAME=<service>
	@$(DOCKER_COMPOSE) down

.PHONY: docker-config
docker-config: validate-docker-variables ## List all configuration in docker-compose file.
	@$(DOCKER_COMPOSE) config

.PHONY: docker-prune
docker-prune: ## Remove ALL unused docker resources, including volumes and images.
	@docker system prune -a -f --volumes

.PHONY: docker-remove-containers
docker-remove-containers: ## Remove ALL containers
	@docker rm -f $(docker ps -aq)
