# Include the ENVs variable
-include make/.env

# Well documented Makefiles
# @see https://www.thapaliya.com/en/writings/well-documented-makefiles/
DEFAULT_GOAL := help
help:
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n"} /^[a-zA-Z0-9_-]+:.*?##/ { printf "  \033[36m%-40s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)

# Include all makefiles from the make folder
include make/*.mk

# Common variable to pass arbitrary options to targets
ARGS?=

##@ [Make]

.PHONY: make-init
make-init: ENVS= ## Initializes the make/.env file with ENV variables for make
make-init:
	@[ -f make/.env ] && echo ".env file exists" || cp make/.env.example make/.env
	@echo "Please update your make/.env file with your settings"

