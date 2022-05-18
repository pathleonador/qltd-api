<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\articles\Article;
use yii\rest\ActiveController;

class ArticleController extends ActiveController
{

    public $modelClass = "api\modules\v1\models\articles\Article";


    public function behaviors()

    {

        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'all' => ['get']
                ]
            ]
        ];
    }


    public function actionAll()
    {
        return (new Article())->getAllArticles();
    }

    public function actionFetch()
    {
        return (new Article())->fetchArticleById();
    }

    public function actionRequest()
    {
        return (new Article())->fetchArticleByBatch();
    }

    public function actionFetchWithVote()
    {
        return (new Article())->fetchArticleByIdWithVote();
    }

    public function actionRequestWithVote()
    {
        return (new Article())->fetchArticleByBatchWithVote();
    }


    public function actionVote()
    {
        return (new Article())->voteForAnArticle();
    }


    public function actionSave()
    {
        return (new Article())->createArticle();
    }

    public function actionEdit()
    {
        return (new Article())->updateArticle();
    }


    public function actionRemove()
    {
        return (new Article())->deleteArticle();
    }


    public function actionTest()
    {
        var_dump(1232, $_SERVER);
        die();
    }
}
