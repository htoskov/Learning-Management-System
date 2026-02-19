@echo off
title Dev Starter

REM Go to project directory
cd /d %~dp0

echo Starting npm dev server...
start "NPM DEV" cmd /k "npm run dev"

echo Starting Laravel server...
start "LARAVEL SERVER" cmd /k "php artisan serve"

echo Starting Stripe webhook listener...
start "STRIPE WEBHOOK" cmd /k ".\stripe listen --forward-to http://127.0.0.1:8000/stripe/webhook"

exit
