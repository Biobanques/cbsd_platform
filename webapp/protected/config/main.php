<?php

// Define a path alias for the Bootstrap extension as it's used internally.
// In this example we assume that you unzipped the extension under protected/extensions.
Yii::setPathOfAlias('bootstrap', dirname(__FILE__) . '/../extensions/bootstrap');

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Biobanques CBSDForms',
    //par defaut en français
    'language' => 'fr',
    // page au démarrage
    //'defaultController' => 'site/login',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'ext.*',
        'ext.bootstrap.*',
        'ext.bootstrap.widgets.*',
        'ext.YiiMongoDbSuite.*',
        'ext.YiiMongoDbSuite.extra.*',
        'application.models.*',
        'application.components.*',
    ),
    /* theme : classic , bootstrap */
    'theme' => 'bootstrap',
    'modules' => array(
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'password',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
            'generatorPaths' => array('ext.YiiMongoDbSuite.gii'),
        ),
    ),
    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
            'class' => 'WebUser',
            'returnUrl' => array('/site/patient'),
        ),
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=cbsdforms',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        ),
        'mongodb' => array(
            'class' => 'EMongoDB',
            'connectionString' => CommonProperties::$CONNECTION_STRING,
            'dbName' => 'cbsdforms',
            'fsyncFlag' => true,
            'safeFlag' => true,
            'useCursor' => false,
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'bootstrap' => array(
            'class' => 'bootstrap.components.Bootstrap',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, trace',
                ),
                array(
                    'class' => 'CWebLogRoute',
                    'levels' => 'error, trace',
                    //'categories'=>'system.db.*',
                    //'except'=>'system.db.ar.*', // shows all db level logs but nothing in the ar category
                    'enabled' => true,
                    'showInFireBug' => true
                ),
            ),
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => CommonProperties::$ADMIN_EMAIL,
        'mailSystemActif' => CommonProperties::$MAIL_SYSTEM_ACTIVE,
    ),
);
