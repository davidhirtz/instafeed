<?php
/**
 * @author David Hirtz <hello@davidhirtz.com>
 */
if (in_array(getenv('YII_ENV'), ['dev', 'local'])) {
    defined('YII_ENV') or define('YII_ENV', getenv('YII_ENV'));
    defined('YII_ENV_DEV') or define('YII_DEV_ENV', true);
    defined('YII_DEBUG') or define('YII_DEBUG', true);
}

ini_set('display_errors', 'On');
error_reporting(E_ALL);

$basePath = dirname(__DIR__) . DIRECTORY_SEPARATOR;
$vendorPath = $basePath . 'vendor' . DIRECTORY_SEPARATOR;

require($vendorPath . 'autoload.php');
require($vendorPath . 'yiisoft/yii2/Yii.php');

(new \davidhirtz\yii2\skeleton\web\Application(require($basePath . 'config' . DIRECTORY_SEPARATOR . YII_ENV . '.php')))->run();
