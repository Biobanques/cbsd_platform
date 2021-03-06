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
    'name' => 'CBSDPlatform',
    //par defaut en français
    'language' => 'fr',
    // page au démarrage
    'defaultController' => 'site/login',
    // preloading 'log' component
    'preload' => array('log', 'maintenanceMode'),
    // autoloading model and component classes
    'import' => array(
        'ext.*',
        'ext.bootstrap.*',
        'ext.bootstrap.widgets.*',
        'ext.MongoDbSuite.*',
        'ext.MongoDbSuite.extra.*',
        'application.models.*',
        'application.components.*',
        'application.modules.auditTrail.models.AuditTrail',
        'application.modules.auditTrail.behaviors.LoggableBehavior',
    ),
    /* theme : classic , bootstrap */
    'theme' => 'bootstrap',
    'modules' => array(
        'auditTrail' => array(),
    ),
    // application components
    'components' => array(
        'maintenanceMode' => array(
            'class' => 'application.extensions.MaintenanceMode.MaintenanceMode',
            'enabledMode' => false,
        ),
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
            'class' => 'WebUser',
            'returnUrl' => array('/site/patient'),
        ),
        'mongodb' => array(
            'class' => 'EMongoDB',
            'connectionString' => CommonProperties::$CONNECTION_STRING,
            'dbName' => CommonProperties::$DBNAME,
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
                    'levels' => 'error, warning',
                ),
                array(
                    'class' => 'CWebLogRoute',
                    'levels' => 'error, warning',
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

