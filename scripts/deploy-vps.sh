#!/usr/bin/env bash
set -euo pipefail

if [[ $# -lt 2 || $# -gt 3 ]]; then
  echo "Usage: ./scripts/deploy-vps.sh /path/to/repo /path/to/laravel-app [php-fpm-service]"
  exit 1
fi

REPO_DIR="$1"
APP_DIR="$2"
PHP_FPM_SERVICE="${3:-}"

if [[ ! -d "$REPO_DIR" ]]; then
  echo "Repository directory not found: $REPO_DIR"
  exit 1
fi

if [[ ! -d "$APP_DIR" ]]; then
  echo "Laravel app directory not found: $APP_DIR"
  exit 1
fi

if [[ ! -f "$APP_DIR/artisan" ]]; then
  echo "Target directory does not look like a Laravel project: $APP_DIR"
  exit 1
fi

echo "Deploying repository into Laravel app..."
bash "$REPO_DIR/scripts/install-into-laravel.sh" "$APP_DIR" --overwrite

cd "$APP_DIR"

echo "Running Laravel maintenance commands..."
php artisan migrate --force
php artisan optimize:clear

if [[ -n "$PHP_FPM_SERVICE" ]]; then
  echo "Restarting service: $PHP_FPM_SERVICE"
  sudo systemctl restart "$PHP_FPM_SERVICE"
fi

echo "Deploy finished."
