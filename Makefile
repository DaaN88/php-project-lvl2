install:
	composer update && composer install
autoload:
	composer dump-autoload
lint:
	composer run-script phpcs -- --standard=PSR12 src bin
.PHONY: test log