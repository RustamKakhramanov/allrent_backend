-include .env
ifeq ($(shell test -e .env && echo -n yes),)
	-include .env.example
endif
 
################### DEFINE VARIABLES
PWD := $(shell pwd)
SAIL :=  ./vendor/bin/sail
ifeq (${MAKEFILE_PHP},)
	MAKEFILE_PHP := ./vendor/bin/sail php
endif
ifeq (${GIT_BRANCH},)
	GIT_BRANCH := dev
endif

define reload
  	${MAKEFILE_PHP} artisan key:generate
  	${MAKEFILE_PHP} artisan optimize:clear
  	${MAKEFILE_PHP} artisan migrate:fresh
  	${MAKEFILE_PHP} artisan passport:install
	${MAKEFILE_PHP} artisan db:seed --force
endef
################### END DEFINE VARIABLES

################### DEFINE METHODS
up:
	@cp $(PWD)/.env.example $(PWD)/.env
ifeq ($(APP_ENV),local)
	${SAIL} up -d 
	${SAIL} composer i  
else
	composer i
endif
	$(call reload)

update:
ifeq (${GIT_URL},)
	@git pull  ${GIT_URL} ${GIT_BRANCH}
endif
	${MAKEFILE_PHP}  artisan migrate  --force
	${MAKEFILE_PHP}  artisan db:seed  --force
	${MAKEFILE_PHP} artisan optimize:clear
	
fresh:
	$(call reload)
################### END DEFINE METHODS