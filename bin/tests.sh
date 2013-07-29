#!/bin/sh

php app/console db:install --test --load-fixtures

./vendor/bin/atoum --test-all
