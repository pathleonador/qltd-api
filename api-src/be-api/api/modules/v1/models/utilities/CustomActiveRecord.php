<?php

namespace api\modules\v1\models\utilities;

use yii\db\ActiveRecord;


abstract class CustomActiveRecord extends ActiveRecord implements ICustomActiveRecord
{
    public $userId;
    public $dbTransaction;

    public function __construct($dbTransaction = null)
    {
        $this->dbTransaction = $dbTransaction;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function saveWithCatch()
    {
        try {
            return $this->save();
        } catch (yii\db\IntegrityException $e) {
            $this->rollbackTransaction();
            var_dump($e);
            die();
        } catch (yii\db\Exception $e) {
            $this->rollbackTransaction();
            var_dump($e);
            die();
        } catch (yii\db\UnknownPropertyException $e) {
            $this->rollbackTransaction();
            var_dump($e);
            die();
        }
    }

    protected function rollbackTransaction()
    {
        if (null != $this->dbTransaction) {
            $this->dbTransaction->rollback();
        }
    }

    public function search()
    {
        return $this->find()
            ->andWhere([
                'is',
                $this->getDeletedAtColumn(),
                new \yii\db\Expression('null')
            ])
            ->andWhere([
                'is',
                $this->getDeletedByColumn(),
                new \yii\db\Expression('null')

            ]);
    }
}

interface ICustomActiveRecord
{
    public function setUserId();
    public function getUserId();
    public function getDeletedByColumn(): String;
    public function getDeletedAtColumn(): String;
    public function getTalblePrefix();
}
