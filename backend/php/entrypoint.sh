#!/bin/bash
set -e

# Cambiar la propiedad de la carpeta de trabajo al usuario app (ID 1000)
# Esto corrige los permisos del volumen montado en tiempo de ejecución
chown -R app:app /var/www/html

# Ejecutar el comando principal (php-fpm)
exec "$@"