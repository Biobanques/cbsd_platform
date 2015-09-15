#!/bin/bash

####################################################################
# script to apply json commands to start the db usage
# @author  nicolas malservet
# @version 1.0 
####################################################################

mongo cbsdforms --port 32020 -u qfuseradmin -p 'bbanques2015' ./parkinson.json.js
mongo cbsdforms --port 32020 -u qfuseradmin -p 'bbanques2015' ./questionnaire.json.js
mongo cbsdforms --port 32020 -u qfuseradmin -p 'bbanques2015' ./questionnaire_cession.json.js
mongo cbsdforms --port 32020 -u qfuseradmin -p 'bbanques2015' ./answer.json.js
mongo cbsdforms --port 32020 -u qfuseradmin -p 'bbanques2015' ./users.json.js