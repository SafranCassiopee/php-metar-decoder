#!/bin/bash
#---------------------------------
# Launch unit tests on the project
#---------------------------------

# regular, with autoloading
./vendor/bin/phpunit tests

# with code coverage report
#./vendor/bin/phpunit --coverage-html ./report tests

# with manual loading
#./vendor/bin/phpunit --bootstrap src/MetarDecoder.inc.php tests
