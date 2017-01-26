install: check php-cs-fixer.phar
	composer install --dev

check:
	@composer --version > /dev/null

php-cs-fixer.phar:
	wget http://get.sensiolabs.org/php-cs-fixer.phar -O php-cs-fixer.phar

clean:
	rm php-cs-fixer.phar
	rm -r report
	rm -r vendor

fix:
	php php-cs-fixer.phar fix ./src/
	php php-cs-fixer.phar fix ./tests/

test:
	./vendor/bin/phpunit tests

test_no_autoload:
	./vendor/bin/phpunit --bootstrap src/MetarDecoder.inc.php tests

coverage:
	./vendor/bin/phpunit --coverage-html ./report tests

.PHONY: install check clean fix test_no_autoload coverage