#! /bin/bash
#------------------------------------
# Fix PHP code style
# need to have php-cs-fixer available
#------------------------------------

php php-cs-fixer.phar fix ./src/
php php-cs-fixer.phar fix ./tests/
