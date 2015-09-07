#!/bin/bash

####################################################################
# script to install cbsdforms
# @author  bernard te
# @version 1.0 
####################################################################

# Log in as Superuser (root user)

cd /var/www/html/

# test existing cbsd_platform project into /var/www/html/, if not, clone the project from GitHub

if [ ! -d "/var/www/html/cbsd_platform/" ];then
    git clone https://github.com/Biobanques/cbsd_platform.git
fi

# create folders "assets" and "runtime" and give the user write access to theses folders 

chmod -R ugo+rwx /var/www/html/cbsd_platform/
mkdir /var/www/html/cbsd_platform/webapp/assets
chmod -R ugo+rwx /var/www/html/cbsd_platform/webapp/assets
mkdir /var/www/html/cbsd_platform/webapp/protected/runtime
chmod -R ugo+rwx /var/www/html/cbsd_platform/webapp/protected/runtime

# copy a new CommonProperties and set database

cp /var/www/html/cbsd_platform/webapp/protected/components/CommonProperties_default_1_1.php /var/www/html/cbsd_platform/webapp/protected/components/CommonProperties.php

DBB="'cbsdforms'"
LOGIN="admin_cbsd"
PASSWORD="cbsd2015"
HOST="localhost"

DATABASE="'mongodb:\/\/$LOGIN:$PASSWORD@$HOST\/$DBB'"
sed -i -e "s/'mongodb:\/\/qfuseradmin:bbanques2015@localhost\/qualityformsdb'/$DATABASE/g" /var/www/html/cbsd_platform/webapp/protected/components/CommonProperties.php
sed -i -e "s/'qualityformsdb'/$DBB/g" /var/www/html/cbsd_platform/webapp/protected/config/main.php