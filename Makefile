install: composer.install tools.install

composer.install:
	composer install

clean:
	rm -rf composer.lock vendor/
	rm -rf tools/composer.lock tools/vendor/

tools.install:
	composer -d tools install

cs:
	tools/vendor/bin/php-cs-fixer fix --verbose --diff --dry-run

cs.fix:
	tools/vendor/bin/php-cs-fixer fix --verbose --diff

phpstan:
	tools/vendor/bin/phpstan

phpunit:
	vendor/bin/phpunit
