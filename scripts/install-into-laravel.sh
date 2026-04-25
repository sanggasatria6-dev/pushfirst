#!/usr/bin/env bash
set -euo pipefail

OVERWRITE=0

if [[ $# -lt 1 || $# -gt 2 ]]; then
  echo "Usage: ./scripts/install-into-laravel.sh /path/to/laravel-project [--overwrite]"
  exit 1
fi

TARGET="$1"

if [[ $# -eq 2 ]]; then
  if [[ "$2" != "--overwrite" ]]; then
    echo "Unknown option: $2"
    exit 1
  fi

  OVERWRITE=1
fi

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

if [[ "$OVERWRITE" -eq 1 ]]; then
  rsync -av "$SOURCE_DIR/" "$TARGET/"
else
  rsync -av --ignore-existing "$SOURCE_DIR/" "$TARGET/"
fi

echo
echo "Done."

if [[ "$OVERWRITE" -eq 1 ]]; then
  echo "Overwrite mode active: target files were updated to match laravel-ready."
else
  echo "Install mode active: existing target files were preserved."
fi

echo "Next steps:"
echo "1. Add middleware alias in bootstrap/app.php"
echo "2. Run: php artisan migrate --seed"
echo "3. Configure .env using docs/SETUP.md"
