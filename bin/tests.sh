#!/bin/sh

php app/console db:install --test --load-fixtures

./vendor/bin/atoum -c .atoum_db.php
./vendor/bin/atoum
