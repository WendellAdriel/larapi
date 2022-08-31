##@ [Application: Commands]

.PHONY: execute-in-container
execute-in-container: ## Execute a command in a container. E.g. via "make execute-in-container DOCKER_SERVICE_NAME=php-fpm COMMAND="echo 'hello'"
	@$(if $(DOCKER_SERVICE_NAME),,$(error DOCKER_SERVICE_NAME is undefined))
	@$(if $(COMMAND),,$(error COMMAND is undefined))
	@$(EXECUTE_IN_ANY_CONTAINER) $(COMMAND)

.PHONY: composer
composer: ## Run composer commands. Specify the command e.g. via "make composer ARGS="install"
	@$(EXECUTE_IN_APPLICATION_CONTAINER) composer $(ARGS);

.PHONY: redis-cli
redis-cli: ## Access redis-cli
	@$(EXECUTE_IN_REDIS_CONTAINER) redis-cli -a 'secret_redis_password';

