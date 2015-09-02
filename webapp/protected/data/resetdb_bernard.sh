#!/bin/bash

####################################################################
# script to apply json commands to start the db usage
# @author  bernard te
# @version 1.0 
####################################################################

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

cp /var/www/html/cbsd_platform/webapp/protected/CommonProperties_default_1_1.php /var/www/html/cbsd_platform/webapp/protected/CommonProperties.php

DATABASE="'mongodb:\/\/admin_cbsd:cbsd2015@localhost\/cbsdforms'"
sed -i -e "s/'mongodb:\/\/qfuseradmin:bbanques2015@localhost\/qualityformsdb'/$DATABASE/g" /var/www/html/cbsd_platform/webapp/protected/CommonProperties.php

# import "questionnaire", "answer" and "users"

mongo cbsdforms --port 27017 -u admin_cbsd -p 'cbsd2015' ./parkinson.json.js
mongo cbsdforms --port 27017 -u admin_cbsd -p 'cbsd2015' ./questionnaire.json.js
mongo cbsdforms --port 27017 -u admin_cbsd -p 'cbsd2015' ./questionnaire_cession.json.js
mongo cbsdforms --port 27017 -u admin_cbsd -p 'cbsd2015' ./answer.json.js
mongo admin --port 27017 -u admin_cbsd -p 'cbsd2015' ./users.json.js