install:
	composer update && composer install
autoload:
	composer dump-autoload
lint:
	composer run-script phpcs -- --standard=PSR12 src bin Tests
test:
	composer exec --verbose phpunit Tests/Functions/GenDiffTest.php
test-coverage:
	composer exec --verbose phpunit Tests/Functions/GenDiffTest.php -- --coverage-clover build/logs/clover.xml