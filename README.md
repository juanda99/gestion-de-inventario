# gestion-de-inventario
Gestión de inventario para centros educativos. Gestor de incidencias para averías.

## Instalación
### Requerimientos
- Sistema operativo Linux
- Base de datos mysql 
- Servidor web con módulo php

### Configuración previa
- Se tiene que copiar el repositorio en el DOCUMENT_ROOT que vaya a tener la aplicación.
- Crear certificados SSL
- Hay que configurar el servidor web, por ejemplo Apache:

*gestioninventario.conf*:
```
<VirtualHost *:80>
        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/gestioninventario/public
        ServerName www.gestioninventario.com
        ServerAlias gestioninventario.com

        RewriteEngine on
        ReWriteCond %{SERVER_PORT} !^443$
        RewriteRule ^/(.*) https://%{HTTP_HOST}/$1 [NC,R,L]

        <Directory /var/www/gestioninventario>
                Options Indexes FollowSymLinks MultiViews
                AllowOverride All
                Order allow,deny
                allow from all
        </Directory>
        ErrorLog ${APACHE_LOG_DIR}/error-gestioninventario.log
        LogLevel warn
        CustomLog ${APACHE_LOG_DIR}/access-gestioninventario.log combined
</VirtualHost>
```

*gestioninventario-ssl.conf*
```
<IfModule mod_ssl.c>
        <VirtualHost _default_:443>
                ServerAdmin webmaster@localhost
                DocumentRoot /var/www/gestioninventario/public
                ServerName www.gestioninventario.com
                ServerAlias gestioninventario.com
                <Directory /var/www/gestioninventario/public>
                        Options FollowSymLinks
                        AllowOverride All
                        Order allow,deny
                        allow from all
                </Directory>
                ErrorLog ${APACHE_LOG_DIR}/error-gestioninventario.log
                LogLevel debug
                CustomLog ${APACHE_LOG_DIR}/access-gestioninventario.log combined

                SSLEngine on
                SSLCertificateFile      /etc/apache2/certificados/gestioninventario.crt
                SSLCertificateKeyFile /etc/apache2/certificados/gestioninventario.key
                <FilesMatch "\.(cgi|shtml|phtml|php)$">
                                SSLOptions +StdEnvVars
                </FilesMatch>
                <Directory /usr/lib/cgi-bin>
                                SSLOptions +StdEnvVars
                </Directory>
        </VirtualHost>
</IfModule>
```
- Se deben configurar en el fichero install/install.sh varias variables:

```
APP_NAME="Aplicación Gestión Incidencias"
SMTP_SERVER="smtp.miservidor.com"
SMTP_USER="miusuario@server.com"
SMTP_PASSWORD="passowrd"
SMTP_EMAIL="no-reply@miservidor.com"
DBADMIN="root"
DBADMIN_PASSWORD="password"
```
- *APP_NAME* es el nombre de la aplicación, y aparece tanto en la web como en los correos electrónicos que se envían.
- *SMTP_SERVER*, *SMTP_USER* y *SMTP_PASSWORD* son los parámetros necesarios para el envío del correo electrónico. Es necesario su funcionamiento para poder activar usuarios.
- *DBADMIN* y *DBADMIN_PASSWORD* se utilizan para poder crear la base de datos.

### Cambio de logo
- Coloca la imagen de tu centro en la pantalla principal, modificando el fichero */public/img/logo.jpg*
- 
### Instalación:
- Ejecutaremos el siguiente script, con el nombre de la bbdd, usuario y contraseña que elijamos
```
.install/install.sh
Usage: ./install.sh dbname dbuser dbpass
```

- Una vez instalado es recomendable borrar la carpeta install

## Acceso
- Mediante la url https://www.gestioninventario.com o la que se haya configurado.
- Puede ser necesario modificar el fichero /etc/hosts y poner una entrada del tipo:
```
127.0.0.1       gestioninventario.com    www.gestioninventario.com
```
- El usuario inicial de acceso es *user@user.com* y la contraseña *juanda*

## Tecnologías
- php en servidor (Zend Framework v1)
- jQuery y CSS Bootstrap v2 en cliente
- Base de Datos MySql
