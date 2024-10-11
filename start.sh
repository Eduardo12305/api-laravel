#!/bin/bash
# wait-for-it.sh

set -e

host="$1"
shift
cmd="$@"

until nc -z "$host" 3306; do
  >&2 echo "Aguardando o banco de dados estar disponível..."
  sleep 1
done

>&2 echo "Banco de dados disponível!"
exec $cmd
