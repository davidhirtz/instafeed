<?php

namespace app\controllers;

use davidhirtz\yii2\skeleton\web\Controller;

/**
* SiteController
*/
class SiteController extends Controller
{
    public function actionIndex()
    {
        return $this->redirect(['/admin']);
    }
}
