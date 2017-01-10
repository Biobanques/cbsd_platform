<?php
//locale du serveur en francais si besoin
setlocale(LC_ALL, 'fr_FR.utf8', 'fra');
ini_set('memory_limit', -1);
set_time_limit(0);
//timezone des dates
date_default_timezone_set('Europe/Paris');
// change the following paths if necessary
$yii=dirname(__FILE__).'/yii-1.1.17.467ff50/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

include dirname(__FILE__) . '/protected/components/CommonProperties.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createWebApplication($config)->run();
