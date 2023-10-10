<?php
/**
 * @noinspection PhpUnhandledExceptionInspection
 */

use davidhirtz\yii2\skeleton\web\Application;

if (in_array(getenv('YII_ENV'), ['dev', 'local'])) {
    defined('YII_ENV') or define('YII_ENV', getenv('YII_ENV'));
    defined('YII_ENV_DEV') or define('YII_DEV_ENV', true);
    defined('YII_DEBUG') or define('YII_DEBUG', true);
}

ini_set('display_errors', 'On');
error_reporting(E_ALL);

if (file_exists($maintenance = __DIR__ . '/../runtime/maintenance.php')) {
    require $maintenance;
}

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

(new Application(require(__DIR__ . '/../config/' . YII_ENV . '.php')))->run();