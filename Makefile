SHELL := /bin/bash

npm=npm --prefix ./web

ligo_compiler?=docker run --rm -v "$(PWD)":"$(PWD)" -w "$(PWD)" ligolang/ligo:0.58.0
# ^ Override this variable when you run make command by make <COMMAND> ligo_compiler=<LIGO_EXECUTABLE>
# ^ Otherwise use default one (you'll need docker)
protocol_opt?=

project_root=--project-root .
# ^ required when using packages

help:
	@grep -E '^[ a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | \
	awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-15s\033[0m %s\n", $$1, $$2}'

compile = $(ligo_compiler) compile contract $(project_root) ./src/$(1) -o ./compiled/$(2) $(3) $(protocol_opt)
# ^ compile contract to michelson or micheline

test = $(ligo_compiler) run test $(project_root) ./test/$(1) $(protocol_opt)
# ^ run given test file

compile: ## compile contracts
	@if [ ! -d ./compiled ]; then mkdir ./compiled ; fi
	@echo "Compiling contracts..."
	@$(call compile,main.mligo,airdrop.tz)
	@$(call compile,main.mligo,airdrop.json,--michelson-format json)
	@echo "Compiled contracts"

clean: ## clean up
	@rm -rf compiled

deploy.js:
	@if [ ! -f ./deploy/metadata.json ]; then cp deploy/metadata.json.dist deploy/metadata.json ; fi
	@echo "Running deploy script\n"
	@cd deploy && npm start

install: ## install dependencies
	@$(ligo_compiler) install
	@if [ ! -f web/.env ]; then cp web/.env.dist web/.env ; fi
	@$(npm) ci

.PHONY: test
test: ## run tests
	@$(call test,all.mligo)

test-js: ## run js tests
	@$(npm) run test

lint: ## lint code
	@$(npm) run lint

sandbox-start: ## start sandbox
	@$(npm) run sandbox:start

sandbox-stop: ## stop sandbox
	@$(npm) run sandbox:stop
