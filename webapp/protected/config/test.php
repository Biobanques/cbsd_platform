<?php

return CMap::mergeArray(
                require(dirname(__FILE__) . '/main.php'), array(
            'import' => array(
                'ext.SeleniumWebTestCase.*',
                'application.controllers.*'
            ),                    
            'components' => array(
                'fixture' => array(
                    'class' => 'system.test.CDbFixtureManager',
                ),
                'mongodb' => array(
                    'class' => 'EMongoDB',
                    'connectionString' => CommonProperties::$CONNECTION_STRING,
                    'dbName' => CommonProperties::$DBNAME,
                    'fsyncFlag' => true,
                    'safeFlag' => true,
                    'useCursor' => false,
                ),
            ),
                )
);
