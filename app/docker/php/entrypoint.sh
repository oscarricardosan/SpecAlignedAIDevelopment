#!/bin/sh
set -e

# Align www-data UID/GID with host to avoid permission issues on bind mounts
if [ -n "$HOST_UID" ] && [ -n "$HOST_GID" ]; then
    usermod -u "$HOST_UID" www-data 2>/dev/null || true
    groupmod -g "$HOST_GID" www-data 2>/dev/null || true
fi

# Fix ownership on storage and bootstrap/cache (may be mounted from host)
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

exec "$@"
