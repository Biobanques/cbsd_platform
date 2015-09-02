#!/bin/bash

####################################################################
# script to apply json commands to start the db usage
# @author  bernard te
# @version 1.0 
####################################################################

if [ ! -d "/var/www/html/cbsd_platform/" ];then
    git clone https://github.com/Biobanques/cbsd_platform.git
fi
chmod -R ugo+rwx /var/www/html/cbsd_platform/
mkdir /var/www/html/cbsd_platform/webapp/assets
chmod -R ugo+rwx /var/www/html/cbsd_platform/webapp/assets
mkdir /var/www/html/cbsd_platform/webapp/protected/runtime
chmod -R ugo+rwx /var/www/html/cbsd_platform/webapp/protected/runtime

cp /var/www/html/cbsd_platform/webapp/protected/CommonProperties_default_1_1.php /var/www/html/cbsd_platform/webapp/protected/CommonProperties.php

MESSAGE="'mongodb:\/\/admin_cbsd:cbsd2015@localhost\/cbsdforms'"
sed -i -e "s/'mongodb:\/\/qfuseradmin:bbanques2015@localhost\/qualityformsdb'/$MESSAGE/g" /var/www/html/cbsd_platform/webapp/protected/CommonProperties.php

mongo cbsdforms --port 27017 -u admin_cbsd -p 'cbsd2015' ./parkinson.json.js
mongo cbsdforms --port 27017 -u admin_cbsd -p 'cbsd2015' ./questionnaire.json.js
mongo cbsdforms --port 27017 -u admin_cbsd -p 'cbsd2015' ./questionnaire_cession.json.js
mongo cbsdforms --port 27017 -u admin_cbsd -p 'cbsd2015' ./answer.json.js
mongo admin --port 27017 -u admin_cbsd -p 'cbsd2015' ./users.json.js