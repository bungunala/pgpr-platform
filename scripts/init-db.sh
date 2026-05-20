#!/bin/bash
set -e

echo "Esperando PostgreSQL..."
sleep 10

cd /var/www/html

echo "Ejecutando migraciones..."
php spark migrate

echo "Ejecutando seeders..."
php spark db:seed DatabaseSeeder

echo "Base de datos lista!"