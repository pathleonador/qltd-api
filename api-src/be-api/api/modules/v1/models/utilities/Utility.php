<?php

namespace api\modules\v1\models\utilities;

use DateTime;
use DateTimeZone;

/**
 * Centralizes application utilities or methods with specific output and encourage reusable codes.     
 * @author Patrocinio Leonador <pleonador@gmail.com> 
 */

class Utility
{
    public static function getArrayKeyValueRequired($array, $key)
    {
        if (!isset($array[$key])) {
            var_dump("Array key `{$key}` is either missing or has ann empty value.");
            die();
        }

        if (empty($array[$key])) {
            var_dump("Array key `{$key}` is either missing or has ann empty value.");
            die();
        }

        return $array[$key];
    }

    public static function getRequestParameterFromGet($parameterName)
    {
        return \Yii::$app->request->get($parameterName);
    }

    public static function getRequestParameterFromGetRequired($parameterName)
    {
        $parameter = \Yii::$app->request->get($parameterName);
        if (null == $parameter) {
            var_dump('reuired field cannot be empty');
            die();
        }

        return $parameter;
    }


    public static function getRequestParameterFromPostRequired($parameterName)
    {
        $parameter = \Yii::$app->request->post($parameterName);
        if (null == $parameter) {
            var_dump('reuired field cannot be empty');
            die();
        }

        return $parameter;
    }

    public static function getCurrentDateTime($format = 'Y-m-d H:i:s')
    {
        $tz = 'UTC';
        date_default_timezone_set($tz);
        $time = (new DateTime())->setTimezone(new DateTimeZone($tz));
        return $time->format($format);
    }

    public static function getRawBodyJsonAsArray()
    {
        $result = json_decode(\Yii::$app->request->getRawBody(), true);
        if (null == $result) {
            var_dump('raw body cannot be empty');
            die();
        }
        return $result;
    }
}
