install:
	composer install
lint:
	composer run-script phpcs -- --standard=PSR2 src bin
test:
	composer run-script test tests/
update:
	composer update