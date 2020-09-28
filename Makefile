install:
	composer update && composer install
autoload:
	composer dump-autoload
lint:
	composer run-script phpcs -- --standard=PSR12 src bin
.PHONY: test log
test:
	composer exec --verbose phpunit src\tests\GenDiffTest.php
test-coverage:
	composer exec --verbose phpunit src\tests\GenDiffTest.php -- --coverage-clover build/logs/clover.xml