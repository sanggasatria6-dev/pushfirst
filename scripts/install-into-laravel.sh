#!/usr/bin/env bash
set -euo pipefail

if [[ $# -ne 1 ]]; then
  echo "Usage: ./scripts/install-into-laravel.sh /path/to/laravel-project"
  exit 1
fi

TARGET="$1"
SOURCE_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)/laravel-ready"

if [[ ! -d "$TARGET" ]]; then
  echo "Target Laravel project not found: $TARGET"
  exit 1
fi

if [[ ! -f "$TARGET/artisan" ]]; then
  echo "Target directory does not look like a Laravel project: $TARGET"
  exit 1
fi

echo "Copying Laravel-ready files into: $TARGET"
rsync -av --ignore-existing "$SOURCE_DIR/" "$TARGET/"

echo
echo "Done."
echo "Next steps:"
echo "1. Review merged files"
echo "2. Add middleware alias in bootstrap/app.php"
echo "3. Run: php artisan migrate --seed"
echo "4. Configure .env using docs/SETUP.md"
