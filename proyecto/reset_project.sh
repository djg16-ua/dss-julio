#!/bin/bash

# Colores para mejor visualización
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}================================${NC}"
echo -e "${BLUE}    REINICIANDO PROYECTO LARAVEL${NC}"
echo -e "${BLUE}================================${NC}"
echo

# Función para verificar si el comando anterior fue exitoso
check_command() {
    if [ $? -ne 0 ]; then
        echo -e "${RED}ERROR: $1${NC}"
        exit 1
    fi
}

echo -e "${YELLOW}[1/5] Limpiando rutas...${NC}"
php artisan route:clear
check_command "No se pudo limpiar las rutas"
echo -e "${GREEN}Rutas limpiadas correctamente${NC}"
sleep 2
echo

echo -e "${YELLOW}[2/5] Limpiando configuración...${NC}"
php artisan config:clear
check_command "No se pudo limpiar la configuración"
echo -e "${GREEN}Configuración limpiada correctamente${NC}"
sleep 2
echo

echo -e "${YELLOW}[3/5] Limpiando cache...${NC}"
php artisan cache:clear
check_command "No se pudo limpiar el cache"
echo -e "${GREEN}Cache limpiado correctamente${NC}"
sleep 2
echo

echo -e "${YELLOW}[4/5] Ejecutando migraciones frescas con seeders...${NC}"
echo -e "${RED}ADVERTENCIA: Esto eliminará todos los datos existentes${NC}"
php artisan migrate:fresh --seed
check_command "No se pudieron ejecutar las migraciones"
echo -e "${GREEN}Migraciones ejecutadas correctamente${NC}"
sleep 3
echo

echo -e "${YELLOW}[5/5] Iniciando servidor de desarrollo...${NC}"
echo -e "${GREEN}El servidor se iniciará en http://localhost:8000${NC}"
echo -e "${YELLOW}Presiona Ctrl+C para detener el servidor${NC}"
sleep 3
echo

php artisan serve