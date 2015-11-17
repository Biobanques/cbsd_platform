
<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'My Console Application',
    // preloading 'log' component
    'preload' => array('log'),
    'import' => array(
        'application.models.*',
        'application.components.*',
        'ext.YiiMongoDbSuite.*',
        'ext.YiiMongoDbSuite.extra.*',
        'application.modules.auditTrail.models.AuditTrail',
        'application.modules.auditTrail.behaviors.LoggableBehavior',
    ),
    // application components
    'components' => array(
        'mongodb' => array(
            'class' => 'EMongoDB',
            'connectionString' => CommonProperties::$CONNECTION_STRING,
            'dbName' => 'cbsdplatformdb',
            'fsyncFlag' => true,
            'safeFlag' => true,
            'useCursor' => false,
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            ),
        ),
    ),
    'params' => array(
        'adminEmail' => CommonProperties::$ADMIN_EMAIL,
        //variable pour activer systeme de mail
        'mailSystemActif' => CommonProperties::$MAIL_SYSTEM_ACTIVE,
        'mailRelanceExport' => false,
    ),
);