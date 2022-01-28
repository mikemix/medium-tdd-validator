ROOT_DIR:=$(shell dirname $(realpath $(firstword $(MAKEFILE_LIST))))
CURRENT_UID := $(shell id -u)
CURRENT_GID := $(shell id -g)
CONTAINER:=tdd_validator

start:
	@docker run -it --rm -d --name $(CONTAINER) -w /app -v $(ROOT_DIR)/app:/app php:7.4-cli

stop:
	@docker stop $(CONTAINER)

bash:
	@docker exec -it $(CONTAINER) bash

deps:
	@docker exec $(CONTAINER) curl https://getcomposer.org/download/latest-2.2.x/composer.phar -so /bin/composer
	@docker exec $(CONTAINER) chmod +x /bin/composer
	@docker exec $(CONTAINER) apt-get update
	@docker exec $(CONTAINER) apt-get install -y libzip-dev git zip libicu-dev
	@docker exec $(CONTAINER) apt-get autoremove -y
	@docker exec $(CONTAINER) docker-php-ext-install intl
	@docker exec $(CONTAINER) composer install -o
	@docker exec $(CONTAINER) rm -rf /tmp/* /var/log/* /var/cache/apt/archives/* /var/tmp/* /root/.composer
	@docker exec $(CONTAINER) chown $(CURRENT_UID):$(CURRENT_GID) . -R

tests:
	@docker exec $(CONTAINER) vendor/bin/phpunit tests --testdox
