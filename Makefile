CURRENT_DIR := $(dir $(abspath $(lastword $(MAKEFILE_LIST))))
CURRENT_UID := $(shell id -u)
DOCKER_COMPOSE = "docker-compose"
SHELL          = /bin/bash
CONTAINER      = feature-flag-webserver
WORKDIR        = /var/www/html/
EXEC           = docker exec -w $(WORKDIR) --user=$(CURRENT_UID) -it $(CONTAINER)
COMPOSER       = $(EXEC) composer


#
# ❓ Help output
#
help: ## Show make targets
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_\-\/]+:.*?## / {sub("\\\\n",sprintf("\n%22c"," "), $$2);printf " \033[36m%-24s\033[0m  %s\n", $$1, $$2}' $(MAKEFILE_LIST)

#
# 🐘 Build and run
#
start: ## Start and run project
	@echo "Building containers . . . 📦"
	@$(DOCKER_COMPOSE) up -d
	@make deps
	@echo "📦 Build done 📦"

stop: ## Stop project
	@echo "Stopping . . . 🔻"
	@$(DOCKER_COMPOSE) down --remove-orphans
	@echo "Stopped . . . ⛔"

rebuild: ## Rebuild wallbox pooling containers
	@echo "🔥 Rebuilding containers 🔥"
	@$(DOCKER_COMPOSE) build --pull --force-rm --no-cache
	@make start
	@echo "🔥 Rebuild done 🔥"

bash: ## Start bash console inside the container
	$(EXEC) /bin/bash

#
# Utilities 📦
#
create_env_file:
	@if [ ! -f .env.local ]; then cp .env .env.local; fi

deps: composer-install

composer-install ci: ACTION=install

composer-update cu: ACTION=update $(module)

composer-require cr: ACTION=require $(module)

composer composer-install ci composer-update cu composer-require cr:
	$(COMPOSER) $(ACTION) \
			--no-ansi \
			--no-scripts
