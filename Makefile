
COMPOSER=composer
SERVER_HOST=localhost:8888
PRODUCTION_HOST=updates.owncloud.com/server/
PRODUCTION_PROTO=https

composer_deps=vendor
composer_dev_deps=vendor/bin/
behat=$(composer_deps)/bin/behat
phpunit=$(composer_deps)/bin/phpunit
server_pid_file=make-test-server.pid

.PHONY: all
all: $(composer_deps)

.PHONY: clean
clean: clean-composer-deps

.PHONY: clean-composer-deps
clean-composer-deps:
	rm -Rf $(composer_deps)

$(composer_deps):
	$(COMPOSER) install --no-dev

$(composer_dev_deps):
	$(COMPOSER) install --dev

$(behat): $(composer_dev_deps)

$(server_pid_file):
	{ php -S $(SERVER_HOST) public/index.php & echo $$! > $@; }

.PHONY: start-server
start-server: $(server_pid_file)

.PHONY: stop-server
stop-server:
	kill $(shell cat $(server_pid_file))
	rm $(server_pid_file)

.PHONY: test
test: $(behat) start-server
	cd tests/integration && $(abspath $(behat)) .
	$(MAKE) stop-server

.PHONY: test-production
test-production:
	cd tests/integration && SERVER_HOST=${PRODUCTION_HOST} SERVER_PROTO=${PRODUCTION_PROTO} $(abspath $(behat)) .

.PHONY: test-php-unit
test-php-unit: $(composer_dev_deps)
	$(phpunit) -c tests/unit/phpunit.xml
