#!/bin/sh

php app/console db:install --env=test --load-fixtures

./bin/atoum
