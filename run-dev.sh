#!/usr/bin/env bash
service apache2 start;
php -S 127.0.0.1:8081 -t ~/phpstormprojects/framework/app-backend/public/ > /dev/null;
cd app-frontend/spa-template && npm run build;
