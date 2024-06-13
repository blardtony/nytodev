# Variables
isDocker := $(shell docker info > /dev/null 2>&1 && echo 1)
isProd := $(shell grep "APP_ENV=prod" .env.local > /dev/null && echo 1)
user := $(shell id -u)
group := $(shell id -g)
test:= "test"
sy := php bin/console

ifeq ($(isDocker), 1)
	ifneq ($(isProd), 1)
		dc := USER_ID=$(user) GROUP_ID=$(group) docker compose
		de := docker compose exec
		dr := $(dc) run --rm
	endif
endif

.DEFAULT_GOAL := help
.PHONY: help
help: ## Affiche l'aide
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: dev
dev: vendor/autoload.php ## Lance le serveur de développement
	$(dc) up

.PHONY: tests
tests: vendor/autoload.php ## Lance les tests
	$(sy) d:s:u -f -e test
	$(sy) doctrine:fixtures:load -e test -n
	$(php) bin/phpunit  --testdox --colors=always

.PHONY: linters
linters: vendor/autoload.php ## Lance le linter
	$(sy) lint:yaml config
	$(sy) lint:twig templates
	$(sy) lint:container
# Dépendances
vendor/autoload.php: composer.lock
	$(php) composer install
	touch vendor/autoload.php