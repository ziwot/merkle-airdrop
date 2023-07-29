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
npm=npm --prefix ./infra -s run
TAQ_LIGO_IMAGE=ligolang/ligo:0.68.0

########################################
#            DEPENDENCIES              #
########################################
install: ##@Dependencies install dependencies
	@npm ci
	@TAQ_LIGO_IMAGE=$(TAQ_LIGO_IMAGE) taq ligo -c "install"

########################################
#               INFRA                  #
########################################
up: ##@Infra start local infra
	taq start sandbox
	$(DOCKER_COMPOSE) up -d --remove-orphans

down: ##@Infra stop local infra
	taq stop sandbox
	$(DOCKER_COMPOSE) down

data-reset: down ##@Infra reset data
	docker volume rm infra_db_data

testdata: bootstrapped ##@Infra generate testdata
	@$(npm) testdata

bootstrapped: ##@Infra check sandbox is bootstrapped
	@$(npm) bootstrapped

deploy: testdata ##@Infra deploy contracts
	taq deploy airdrop.tz

########################################
#             CONTRACTS                #
########################################
compile: ##@Contracts compile contracts
	@TAQ_LIGO_IMAGE=$(TAQ_LIGO_IMAGE) taq compile-all

test: ##@Contracts test contracts
	@TAQ_LIGO_IMAGE=$(TAQ_LIGO_IMAGE) taq ligo -c "run test contracts/test/all.mligo"

generate-types: compile ##@Contracts generate types
	taq generate types artifacts/nft.tz artifacts/delegation.tz --typescriptDir ./app/src/types

########################################
#                 APP                  #
########################################
start: ##@App start app
	@if [ ! -f ./app/.env ]; then cp ./app/config/.env.example  ./app/config/.env; fi
	./app/bin/cake server
