#!/bin/bash

####################################################################
# script to apply json commands to start the db usage
# @author  nicolas malservet
# @version 1.0 
####################################################################

DBB="cbsdplatformdb"
LOGIN="admin_cbsd"
PASSWORD="cbsd2015"
HOST="localhost"
PORT="27017"

# Create admin user and set admin acces privilege 
# mongo $HOST:$PORT/admin
# use admin
# db.createUser( {user: "admin_cbsd",pwd: "cbsd2015",roles: [ { role: "userAdmin", db: "admin" } ]})
# db.grantRolesToUser("admin_cbsd", [ { role: "userAdminAnyDatabase", db: "admin" }])

# Create database "cbsdplatformdb"
# use cbsdplatformdb

# Reset dabatase and import datas

mongo $DBB --port $PORT -u $LOGIN -p $PASSWORD ./parkinson.json.js
mongo $DBB --port $PORT -u $LOGIN -p $PASSWORD ./answer.json.js
mongo $DBB --port $PORT -u $LOGIN -p $PASSWORD ./users.json.js
