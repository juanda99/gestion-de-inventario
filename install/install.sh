#!/bin/bash 
##########################################################
############## CONFIGURACIÓN PREVIA ######################
#########################################################
APP_NAME="Aplicación Gestión Incidencias"
SMTP_SERVER="smtp.miservidor.com"
SMTP_USER="miusuario@server.com"
SMTP_PASSWORD="passowrd"
SMTP_EMAIL="no-reply@miservidor.com"
DBADMIN="root"
DBADMIN_PASSWORD="password"


EXPECTED_ARGS=3
E_BADARGS=65
MYSQL=`which mysql`
  
Q0="DROP DATABASE IF EXISTS $1;"
Q1="CREATE DATABASE $1 DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;"
Q2="GRANT USAGE ON $1.* TO $2@localhost IDENTIFIED BY '$3';"
Q3="GRANT ALL PRIVILEGES ON $1.* TO $2@localhost;"
Q4="FLUSH PRIVILEGES;"
Q5="source estructura.sql;"
SQL="${Q0}${Q1}${Q2}${Q3}${Q4}"
  
if [ $# -ne $EXPECTED_ARGS ]
then
  echo "Usage: $0 dbname dbuser dbpass"
  exit $E_BADARGS
fi
  
$MYSQL -u$DBADMIN -p$DBADMIN_PASSWORD -e "$SQL"

$MYSQL -u$2 -p$3 $1<estructura.sql 
#$MYSQL -u$2 -p$3 $1<datos.sql 

#modificamos los scripts de configuración:
#primero por si tuvieran alguna modificación previa, los reescribimos:
cp ../application/configs/application.ini.original ../application/configs/application.ini
cp ../public/php/mysql.php.original ../public/php/mysql.php
sed -i 's/basededatos/'"$1"'/g' ../application/configs/application.ini
sed -i 's/usuariobbdd/'"$2"'/g' ../application/configs/application.ini
sed -i 's/contraseñabbdd/'"$3"'/g' ../application/configs/application.ini
sed -i 's/basededatos/'"$1"'/g' ../public/php/mysql.php
sed -i 's/usuariobbdd/'"$2"'/g' ../public/php/mysql.php
sed -i 's/contraseñabbdd/'"$3"'/g' ../public/php/mysql.php
sed -i 's/smtp-server/'"$SMTP_SERVER"'/g' ../application/controllers/CorreosController.php
sed -i 's/smtp-user/'"$SMTP_USER"'/g' ../application/controllers/CorreosController.php
sed -i 's/smtp-password/'"$SMTP_PASSWORD"'/g' ../application/controllers/CorreosController.php
sed -i 's/email-address/'"$SMTP_EMAIL"'/g' ../application/controllers/CorreosController.php
sed -i 's/app-name/'"$APP_NAME"'/g' ../application/controllers/CorreosController.php
sed -i 's/app-name/'"$APP_NAME"'/g' ../application/layouts/scripts/layout.phtml
sed -i 's/app-name/'"$APP_NAME"'/g' ../application/views/scripts/index/index.phtml
sed -i 's/app-name/'"$APP_NAME"'/g' ../application/models/Usuarios.php

