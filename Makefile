help:           ## Show this help
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'

all: install cs test

install: ## install dependencies
	composer install

cs: ## Check code style
	./vendor/bin/phpcs --config-set ignore_warnings_on_exit 1
	./vendor/bin/phpcs --colors --standard=PSR2 src tests

csfix: ## Fix Code Style
	php vendor/bin/phpcbf

test: ## Run tests
	php vendor/bin/phpunit

mess: ## Run mess detector
	./vendor/bin/phpmd src text ./phpmd.xml

check-mess: ## Run mess detector
	./vendor/bin/phpmd src text ./phpmd.xml

build:
	$(MAKE) cs
	$(MAKE) mess
	$(MAKE) test

travis-build:
	$(MAKE) cs
	$(MAKE) mess
	$(MAKE) test-coverage
