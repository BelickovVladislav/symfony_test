#!/bin/bash

ENV_FILE=.env 
if [ -f "$ENV_FILE" ];
then
    rm -f "$ENV_FILE"
fi

echo "APP_ENV=$APP_ENV" >> "$ENV_FILE"
echo "APP_SECRET=$APP_SECRET" >> "$ENV_FILE"
echo "DATABASE_URL=$DATABASE_DRIVER://$DATABASE_USER:$DATABASE_PASSWORD@$DATABASE_HOST:$DATABASE_PORT/$DATABASE_NAME"  >> "$ENV_FILE"

composer install
php bin/console  --no-interaction doctrine:migrations:migrate