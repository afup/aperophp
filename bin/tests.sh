#!/bin/sh

php app/console db:install --test --load-fixtures

./vendor/bin/atoum -d tests/units
