#!/usr/bin/env sh
set -e

php artisan config:clear
php artisan migrate --force
php artisan db:seed --class=RolePermissionSeeder --force
php artisan db:seed --class=ThreatRuleSeeder --force
php artisan db:seed --class=SystemSettingSeeder --force
php artisan storage:link || true

php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
