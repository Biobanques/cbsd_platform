<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

// change the following paths if necessary
$yiit = dirname(__FILE__) . '/../../yii-1.1.17.467ff50/framework/yiit.php';
$config = dirname(__FILE__) . '/../config/test.php';
include dirname(__FILE__) . '/../../protected/components/CommonProperties.php';

require_once($yiit);


Yii::createWebApplication($config);
