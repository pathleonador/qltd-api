<?php

namespace api\modules\v1\models\articles;

use api\modules\v1\models\utilities\JSONResponse;
use api\modules\v1\models\utilities\Utility;

class Article
{

    public function getAllArticles()
    {
        $allArticles = (new ArticleData())->search()->asArray()->all();
        $responseCode = 200;
        if (null == $allArticles) {
            $responseCode = 404;
        }
        return JSONResponse::generateResponse(null, $allArticles, $responseCode);
    }

    public function fetchArticleById()
    {
        $id = Utility::getRequestParameterFromGetRequired('id');
        $article = (new ArticleData())->search()->andWhere([
            'article_id' => $id
        ])->asArray()->one();

        $responseCode = 200;
        if (null == $article) {
            $responseCode = 404;
        }

        return JSONResponse::generateResponse(null, $article, $responseCode);
    }

    public function fetchArticleByBatch()
    {
        $page = (Utility::getRequestParameterFromGet('page') ?? 1) - 1;
        $limit = Utility::getRequestParameterFromGet('limit') ?? 2;

        $allArticlesQuery = (new ArticleData())->search();
        $count = $allArticlesQuery->count();
        $allArticlesQuery->offset($page * $limit)->limit($limit);
        $allArticles = $allArticlesQuery->asArray()->all();
        $responseCode = 200;

        if (null == $allArticles) {
            $responseCode = 404;
        }

        return JSONResponse::generateResponse(null, ['total_records' => $count, 'content' => $allArticles], $responseCode);
    }


    public function fetchArticleByIdWithVote()
    {
        $id = Utility::getRequestParameterFromGetRequired('id');
        $allArticles = (new ArticleData())->search()
            ->with([
                'votes' => function ($q) {
                    $q->select(
                        'vote_article_id, vote_casted_vote, count(*) as vote_count'
                    )->where([
                        'vote_deleted_at' => null,
                        'vote_deleted_at' => null
                    ])
                        ->distinct()
                        ->groupBy([
                            'vote_article_id',
                            'vote_casted_vote'
                        ]);
                }
            ], true)
            ->andWhere([
                'article_id' => $id
            ])->asArray()->one();

        $responseCode = 200;

        if (null == $allArticles) {
            $responseCode = 404;
        }


        return JSONResponse::generateResponse(null, $allArticles, $responseCode);
    }

    public function fetchArticleByBatchWithVote()
    {
        $page = (Utility::getRequestParameterFromGet('page') ?? 1) - 1;
        $limit = Utility::getRequestParameterFromGet('limit') ?? 2;

        $allArticlesQuery = (new ArticleData())->search()
            ->with([
                'votes' => function ($q) {
                    $q->select(
                        'vote_article_id, vote_casted_vote, count(*) as vote_count'
                    )->where([
                        'vote_deleted_at' => null,
                        'vote_deleted_at' => null
                    ])
                        ->distinct()
                        ->groupBy([
                            'vote_article_id',
                            'vote_casted_vote'
                        ]);
                }
            ], true);
        $count = $allArticlesQuery->count();
        $allArticlesQuery->offset($page * $limit)->limit($limit);
        $allArticles = $allArticlesQuery->asArray()->all();
        $responseCode = 200;

        if (null == $allArticles) {
            $responseCode = 404;
        }

        return JSONResponse::generateResponse(null, ['total_records' => $count, 'content' => $allArticles], $responseCode);
    }

    public function voteForAnArticle()
    {
        $id = Utility::getRequestParameterFromPostRequired('id');
        $vote = Utility::getRequestParameterFromPostRequired('vote');
        $userCode = Utility::getRequestParameterFromPostRequired('user_code');

        $article = (new ArticleData())->search()->andWhere([
            'article_id' => $id
        ])->asArray()->one();

        if (null == $article) {
            return JSONResponse::generateResponse(null, ['total_records' => 0, 'content' => ['Article not found.']], 404);
        }

        // get previous vote, include soft deleted in the result
        $previousVote = (new VoteData())->find()
            ->where([
                'vote_article_id' => $id,
                'vote_user_id' => $userCode
            ])->one();

        if (null != $previousVote) {
            // update vote

            $prevCastedVote = Utility::getArrayKeyValueRequired($previousVote, 'vote_casted_vote');
            // if vote is deleted, reactivate vote
            if (!is_null($previousVote->vote_deleted_at)) {
                $previousVote->vote_deleted_at = null;
                $previousVote->vote_deleted_by = null;
                $previousVote->vote_updated_at = Utility::getCurrentDateTime();
                $previousVote->vote_updated_by = $userCode;
                $previousVote->vote_casted_vote = $vote;
            } else {

                // delete existing votes
                if (strtoupper($vote) == strtoupper($prevCastedVote)) {
                    $previousVote->vote_deleted_at = Utility::getCurrentDateTime();
                    $previousVote->vote_deleted_by = $userCode;
                    $previousVote->vote_updated_at = Utility::getCurrentDateTime();
                    $previousVote->vote_updated_by = $userCode;
                    $previousVote->vote_casted_vote = $vote;
                } else {
                    // update existing vote
                    $previousVote->vote_updated_at = Utility::getCurrentDateTime();
                    $previousVote->vote_updated_by = $userCode;
                    $previousVote->vote_casted_vote = $vote;
                }
            }
            $previousVote->save();
        } else {
            // create vote 
            $newVote = new VoteData();
            $newVote->vote_user_id = $userCode;
            $newVote->vote_article_id = $id;
            $newVote->vote_casted_vote = $vote;
            $newVote->vote_created_at = Utility::getCurrentDateTime();
            $newVote->vote_created_by = $userCode;
            $newVote->vote_updated_at = Utility::getCurrentDateTime();
            $newVote->vote_updated_by = $userCode;
            $newVote->save();
        }

        return JSONResponse::generateResponse(null, ['total_records' => 0, 'content' => ['Record Created.']], 201);
    }

    public function createArticle()
    {

        $data = Utility::getRawBodyJsonAsArray();

        $article = new ArticleData();
        $article->article_title = Utility::getArrayKeyValueRequired($data, 'title');
        $article->article_description = Utility::getArrayKeyValueRequired($data, 'description');
        $article->article_created_at = Utility::getCurrentDateTime();
        $article->article_created_by = Utility::getArrayKeyValueRequired($data, 'created_by');
        $article->article_updated_at = Utility::getCurrentDateTime();
        $article->article_updated_by = Utility::getArrayKeyValueRequired($data, 'created_by');
        $article->save();

        return JSONResponse::generateResponse(null, ['total_records' => 0, 'content' => ['Record Created.']], 201);
    }

    public function updateArticle()
    {

        $data = Utility::getRawBodyJsonAsArray();

        $id = Utility::getArrayKeyValueRequired($data, 'id');
        $article = (new ArticleData())->search()
            ->andWhere([
                'article_id' => $id
            ])->one();
        if (null != $article) {
            $article->article_title = Utility::getArrayKeyValueRequired($data, 'title');
            $article->article_description = Utility::getArrayKeyValueRequired($data, 'description');
            $article->article_created_at = Utility::getCurrentDateTime();
            $article->article_created_by = Utility::getArrayKeyValueRequired($data, 'created_by');
            $article->article_updated_at = Utility::getCurrentDateTime();
            $article->article_updated_by = Utility::getArrayKeyValueRequired($data, 'created_by');
            $article->save();
        }
        return JSONResponse::generateResponse(null, ['total_records' => 0, 'content' => ['Record Updated.']]);
    }

    public function deleteArticle()
    {
        $id = Utility::getRequestParameterFromGetRequired('id');
        $userCode = Utility::getRequestParameterFromGetRequired('user_code');
        $article = (new ArticleData())->search()
            ->andWhere([
                'article_id' => $id
            ])->one();
        if (null != $article) {
            $article->article_updated_at = Utility::getCurrentDateTime();
            $article->article_updated_by = $userCode;
            $article->article_deleted_at = Utility::getCurrentDateTime();
            $article->article_deleted_by = $userCode;
            $article->save();

            return JSONResponse::generateResponse(null, ['total_records' => 0, 'content' => ['Data deeleted.']], 204);
        }
        return JSONResponse::generateResponse(null, ['total_records' => 0, 'content' => ['No Record Found.']], 404);
    }
}
