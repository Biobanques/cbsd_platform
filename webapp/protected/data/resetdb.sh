#!/bin/bash

####################################################################
# script to apply json commands to start the db usage
# @author  nicolas malservet
# @version 1.0 
####################################################################

# Create admin user and set admin acces privilege 
# mongo
# db.createUser( {user: "admin_cbsd",pwd: "cbsd2015",roles: [ { role: "userAdmin", db: "cbsdforms" } ]})
# db.grantRolesToUser("admin_cbsd", [ { role: "userAdminAnyDatabase", db: "cbsdforms" }])


# Reset dabatase and import datas
 
DBB="cbsdforms"
LOGIN="admin_cbsd"
PASSWORD="cbsd2015"
HOST="localhost"
PORT="27017"

mongo $DBB --port $PORT -u $LOGIN -p $PASSWORD ./parkinson.json.js
mongo $DBB --port $PORT -u $LOGIN -p $PASSWORD ./questionnaire.json.js
mongo $DBB --port $PORT -u $LOGIN -p $PASSWORD ./questionnaire_cession.json.js
mongo $DBB --port $PORT -u $LOGIN -p $PASSWORD ./answer.json.js
mongo $DBB --port $PORT -u $LOGIN -p $PASSWORD ./users.json.js