#!/bin/sh
set -eu

if [ -n "${ADMIN_EMAIL:-}" ] || [ -n "${ADMIN_PASSWORD:-}" ]; then
    if [ -z "${ADMIN_EMAIL:-}" ] || [ -z "${ADMIN_PASSWORD:-}" ]; then
        echo "Set both ADMIN_EMAIL and ADMIN_PASSWORD to enable the demo admin account." >&2
        exit 1
    fi

    php artisan db:seed --class=AdminUserSeeder --force
fi

exec "$@"
