#!/bin/bash

set -e

php artisan migrate

php artisan db:seed
