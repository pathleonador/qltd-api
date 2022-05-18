<?php

namespace api\modules\v1\models\articles;

use api\modules\v1\models\utilities\CustomActiveRecord;

class ArticleData extends CustomActiveRecord
{
    public static function tableName()
    {
        return 'exam.article';
    }

    public function getTalblePrefix()
    {
        return 'article_';
    }

    public function setUserId()
    {
        $this->userId = $this->article_created_by;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getDeletedAtColumn(): string
    {
        return 'article_deleted_at';
    }

    public function getDeletedByColumn(): string
    {
        return 'article_deleted_by';
    }

    public function getVotes()
    {
        return $this->hasMany(VoteData::className(), [
            'vote_article_id' => 'article_id'
        ]);
    }
}
