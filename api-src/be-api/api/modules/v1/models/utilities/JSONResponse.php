<?php

namespace api\modules\v1\models\utilities;

class JSONResponse
{

    public static function setHeaderDataAsJson()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = \Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/json');
    }


    public static function generateResponse($message, $content)
    {
        JSONResponse::setHeaderDataAsJson();
        return json_encode([
            'message' => $message,
            'content' => $content
        ]);
    }
}
