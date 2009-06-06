<?php

// change the following paths if necessary
$yii = 'D:/projects/yii-1.0.5.r1018/framework/yii.php';
$config = dirname(__FILE__).'/protected/config/main.php';

// remove the following line when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);

require_once($yii);
Yii::createWebApplication($config)->run();