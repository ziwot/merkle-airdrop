default: help

# Perl Colors, with fallback if tput command not available
GREEN  := $(shell command -v tput >/dev/null 2>&1 && tput -Txterm setaf 2 || echo "")
BLUE   := $(shell command -v tput >/dev/null 2>&1 && tput -Txterm setaf 4 || echo "")
WHITE  := $(shell command -v tput >/dev/null 2>&1 && tput -Txterm setaf 7 || echo "")
YELLOW := $(shell command -v tput >/dev/null 2>&1 && tput -Txterm setaf 3 || echo "")
RESET  := $(shell command -v tput >/dev/null 2>&1 && tput -Txterm sgr0 || echo "")

# Add help text after each target name starting with '\#\#'
# A category can be added with @category
HELP_FUN = \
    %help; \
    while(<>) { push @{$$help{$$2 // 'options'}}, [$$1, $$3] if /^([a-zA-Z\-]+)\s*:.*\#\#(?:@([a-zA-Z\-]+))?\s(.*)$$/ }; \
    print "usage: make [target]\n\n"; \
    for (sort keys %help) { \
    print "${WHITE}$$_:${RESET}\n"; \
    for (@{$$help{$$_}}) { \
    $$sep = " " x (32 - length $$_->[0]); \
    print "  ${YELLOW}$$_->[0]${RESET}$$sep${GREEN}$$_->[1]${RESET}\n"; \
    }; \
    print "\n"; }

help:
	@perl -e '$(HELP_FUN)' $(MAKEFILE_LIST)

SHELL=/bin/bash
DOCKER_COMPOSE = docker compose -f ./infra/docker-compose.yml

########################################
#            DEPENDENCIES              #
########################################
install: ##@Dependencies install dependencies
	@npm ci
	@cd ./infra && npm ci && cd ..
	@taq ligo -c "install"
	@cd app/ \
		&& composer install \
		&& npm ci \
		&& cp config/app_local.example.php config/app_local.php \
		&& cd ..

########################################
#               INFRA                  #
########################################
up: ##@Infra start local infra
	@taq start sandbox
	@$(DOCKER_COMPOSE) up -d --remove-orphans

down: ##@Infra stop local infra
	@taq stop sandbox
	@$(DOCKER_COMPOSE) down

testdata: bootstrapped ##@Infra generate testdata
	@npm --prefix ./infra -s run testdata
	@cd ./app && ./bin/cake migrations migrate \
	&& ./bin/cake migrations seed \
	&& cd ..

data-reset: down ##@Infra reset data
	@docker volume rm infra_db_data

bootstrapped: ##@Infra check sandbox is bootstrapped
	@npm --prefix ./infra -s run bootstrapped

deploy: testdata ##@Infra deploy contracts
	@taq deploy airdrop.tz

########################################
#             CONTRACTS                #
########################################
compile: ##@Contracts compile contracts
	taq compile-all

test: ##@Contracts test contracts
	taq ligo -c "run test contracts/test/all.mligo"

generate-types: compile ##@Contracts generate types
	@taq generate types artifacts/nft.tz artifacts/delegation.tz --typescriptDir ./app/src/types

########################################
#                 APP                  #
########################################
start: ##@App start app
	@./app/bin/cake server \
		& npm --prefix ./app -s run dev

config: ##@App swap app config (ENV=dev make config)
	@cp "./app/config/app_local.$(ENV).php" ./app/config/app_local.php ; \
	sed -i "s/__SALT__/$(shell openssl rand -base64 32 | tr -d /=+)/" ./app/config/app_local.php ; \
	echo "[OK] environment : $(ENV)"

lint: ##@App lint code
	@cd ./app && composer run-script cs-check && cd ..

fmt: ##@App format code
	@cd ./app && composer run-script cs-fix && cd ..
