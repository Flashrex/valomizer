#!/bin/sh

# Wait for MongoDB to be ready (ordered on wish)
sleep 10

# Now run your Laravel startup command
php artisan app:startup-command

exec "$@"