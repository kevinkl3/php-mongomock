.PHONY: help install lint stan test composer-validate

COMPOSER ?= $(shell command -v composer 2>/dev/null || echo ./composer.phar)
PHPUNIT ?= ./vendor/bin/phpunit
PHPUNIT_FLAGS ?= --configuration phpunit.xml

help:
	@echo "Available targets:"
	@echo "  make install          Install PHP dependencies via Composer."
	@echo "  make lint             Run parallel-lint syntax checking."
	@echo "  make stan             Run PHPStan static analysis."
	@echo "  make test             Run unit test suite."
	@echo "  make composer-validate Validate composer.json and composer.lock."

install:
	# check if composer is installed
	if [ ! -f $(COMPOSER) ]; then \
		curl -sS https://getcomposer.org/installer | php; \
	fi
	# install dependencies
	$(COMPOSER) install

lint:
	./vendor/bin/parallel-lint --exclude .git --exclude vendor .

stan:
	./vendor/bin/phpstan analyze ./src/ --memory-limit 1g

test:
	$(PHPUNIT) $(PHPUNIT_FLAGS)

composer-validate:
	$(COMPOSER) validate

