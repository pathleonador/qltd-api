<?php

namespace api\modules\v1\controllers;

use yii\rest\ActiveController;

class TestController extends ActiveController
{

    public $modelClass = "api\modules\v1\models\Test";

    public function actionTest()
    {
        var_dump(1234, $_SERVER);
        die();
    }
}
