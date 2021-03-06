#!/bin/bash

#this file run the tests suites using selenium RC with the localhost url
# options de choix de navigateur : *safari, *googlechrome, *firefox
# selenium RC need an absolute path to the files that will be injected 
# in 2014 , google chrome will be the browser most used so we recommand to use it for automated test if selenium grid is not used 
#rebuild db before and aftere tests

CURRENTPATH=$(pwd)

java -jar selenium-server-standalone-2.44.0.jar -log selenium_vsc.log -htmlSuite "*googlechrome" "http://localhost" $CURRENTPATH"/dev/testsSuites/ClinicienTestSuite.html" $CURRENTPATH"/results_clinicien.html"

java -jar selenium-server-standalone-2.44.0.jar -log selenium_vsc.log -htmlSuite "*googlechrome" "http://localhost" $CURRENTPATH"/dev/testsSuites/NeuropathologisteTestSuite.html" $CURRENTPATH"/results_neuropathologiste.html"

java -jar selenium-server-standalone-2.44.0.jar -log selenium_vsc.log -htmlSuite "*googlechrome" "http://localhost" $CURRENTPATH"/dev/testsSuites/GeneticienTestSuite.html" $CURRENTPATH"/results_geneticien.html"
