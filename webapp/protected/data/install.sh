#!/bin/bash

####################################################################
# script to install cbsdforms
# @author  bernard te
# @version 1.0 
####################################################################

PATH="/var/www/html/cbsd_platform"

# Log in as Superuser (root user)

cd /var/www/html/

# test existing cbsd_platform project into /var/www/html/, if not, clone the project from GitHub

if [ ! -d "$PATH/" ];then
    git clone https://github.com/Biobanques/cbsd_platform.git
fi

# create folders "assets" and "runtime" and give the user write access to theses folders 

chmod -R ugo+rwx $PATH/
mkdir $PATH/webapp/assets
chmod -R ugo+rwx $PATH/webapp/assets
mkdir $PATH/webapp/protected/runtime
chmod -R ugo+rwx $PATH/webapp/protected/runtime

# copy a new CommonProperties and set database

cp $PATH/webapp/protected/components/CommonProperties_default.php $PATH/webapp/protected/components/CommonProperties.php
chmod ugo+rwx $PATH/webapp/protected/components/CommonProperties.php

DBB="'cbsdplatformdb'"
LOGIN="admin_cbsd"
PASSWORD="cbsd2015"
HOST="localhost"

DATABASE="'mongodb:\/\/$LOGIN:$PASSWORD@$HOST\/$DBB'"
sed -i -e "s/'mongodb:\/\/qfuseradmin:bbanques2015@localhost\/qualityformsdb'/$DATABASE/g" $PATH/webapp/protected/components/CommonProperties.php
sed -i -e "s/'qualityformsdb'/$DBB/g" $PATH/webapp/protected/config/main.php

# reset database
./resetdb.sh