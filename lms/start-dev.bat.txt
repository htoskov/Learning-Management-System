@echo off
title Dev Starter

REM Go to project directory (optional)
cd /d %~dp0

echo Starting npm dev server...
start "NPM DEV" cmd /k "npm run dev"

echo Starting Laravel server...
start "LARAVEL SERVER" cmd /k "php artisan serve"

exit
