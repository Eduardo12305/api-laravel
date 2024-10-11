#!/bin/bash
# start.sh

set -e

host="$1"
shift

# Aguardando o banco de dados estar disponível
until nc -z "$host" 3306; do
  >&2 echo "Aguardando o banco de dados estar disponível..."
  sleep 1
done

>&2 echo "Banco de dados disponível!"

# Executando os comandos
php artisan migrate --force
php artisan serve --host=0.0.0.0 --port=9000
