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

LIGO_PROJECT_ROOT=./contract
SANDBOX_IMAGE=ghcr.io/tez-capital/tezbox:tezos-v22.1
SANDBOX_NAME=sandbox-airdrop
SANDBOX_RPC_PORT=20000
SANDBOX_SCRIPT=riobox
TOKEN_ADDR=$(shell cat ./infra/testdata/token.json | jq -r)
MERKLE_ROOT=$(shell cat ./infra/testdata/merkleRoot.json | jq -r)
AIRDROP_STORAGE=$(shell cat ./infra/testdata/airdrop_storage.tz)

########################################
#            DEPENDENCIES              #
########################################
install: ##@Dependencies install dependencies
	@ligo install --project-root $(LIGO_PROJECT_ROOT)
		&& cd ../infra && npm ci \
		&& cd ../app  && composer install \
		&& cd ..

########################################
#               INFRA                  #
########################################
up: testaccounts ##@Infra start local infra
	@docker run --rm --name $(SANDBOX_NAME) --detach -p $(SANDBOX_RPC_PORT):20000 -e block_time=5 \
		-v $(pwd)/infra/testdata/accounts.hjson:/tezbox/overrides/accounts.hjson \
		$(SANDBOX_IMAGE) $(SANDBOX_SCRIPT) start

down: ##@Infra stop local infra
	@docker stop $(SANDBOX_NAME)

testaccounts:
	@npm --prefix ./infra -s run make:accounts

testdata: bootstrapped ##@Infra generate testdata
	@npm --prefix ./infra -s run make:drops
	@npm --prefix ./infra -s run make:token
	@npm --prefix ./infra -s run make:proof

bootstrapped: ##@Infra check sandbox is bootstrapped
	@npm --prefix ./infra -s run bootstrapped

########################################
#             CONTRACT                 #
########################################
compile: ##@Contract compile contract
	@if [[ -d "$(LIGO_PROJECT_ROOT)/build" ]]; then rm -rf $(LIGO_PROJECT_ROOT)/build ; fi
	@mkdir $(LIGO_PROJECT_ROOT)/build
	@ligo compile contract ./contract/src/airdrop.mligo \
		--project-root $(LIGO_PROJECT_ROOT) \
		--michelson-format text --output-file $(LIGO_PROJECT_ROOT)/build/airdrop.tz

compile-storage: ##@Contract compile contract storage
	@cd ./contract \
		&& ligo compile storage src/airdrop.mligo \
		'generate_initial_storage(0x01, (("$(TOKEN_ADDR)": address), 0n), $(MERKLE_ROOT), (Big_map.empty : Storage.claimed))'\
		--michelson-format 'text' \
		-o ../infra/testdata/airdrop_storage.tz \
		&& cd ..

test: ##@Contract test contract
	@ligo run test contract/tests/all.mligo --project-root $(LIGO_PROJECT_ROOT)

deploy: ##@Contract deploy contract
	octez-client originate contract airdrop_dev transferring 0 from alice running $(LIGO_PROJECT_ROOT)/build/airdrop.tz \
		--init '$(AIRDROP_STORAGE)' \
		--burn-cap 2

########################################
#                 APP                  #
########################################
config: ##@App swap app config (ENV=dev make config)
	@cp "./app/config/app_local.$(ENV).php" ./app/config/app_local.php ; \
	sed -i "s/__SALT__/$(shell openssl rand -base64 32 | tr -d /=+)/" ./app/config/app_local.php ; \
	echo "[OK] environment : $(ENV)"

data-reset:
	@cd ./app && ./bin/cake migrations migrate \
		&& ./bin/cake migrations seed \
		&& cd ..

########################################
#                 QA                   #
########################################
cs-check: ##@QA lint code
	@cd ./app \
		&& composer run-script cs-check \
		&& cd ..

cs-fix: ##@QA format code
	@cd ./app \
		&& composer run-script cs-fix \
		&& cd ..

static-check: ##@QA
	@cd ./app \
	 && composer run-script stan \
	 && cd ..
