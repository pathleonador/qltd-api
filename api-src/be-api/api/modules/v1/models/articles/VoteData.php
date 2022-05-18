<?php

namespace api\modules\v1\models\articles;

use api\modules\v1\models\utilities\CustomActiveRecord;

class VoteData extends CustomActiveRecord
{
    public static function tableName()
    {
        return 'exam.vote';
    }

    public function getTalblePrefix()
    {
        return 'vote_';
    }

    public function setUserId()
    {
        $this->userId = $this->vote_created_by;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getDeletedAtColumn(): string
    {
        return 'vote_deleted_at';
    }

    public function getDeletedByColumn(): string
    {
        return 'vote_deleted_by';
    }
}
