<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\ResultSetInterface;

class UsersArticlesLikesTable extends Table
{
    public function initialize(array $config): void
    {
        $this->setTable('users_articles_likes');
        $this->setPrimaryKey('id');
        $this->setDisplayField(['user_id', 'article_id']);

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('Articles', [
            'foreignKey' => 'article_id',
            'joinType' => 'INNER',
        ]);

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created_at' => 'new',
                    'updated_at' => 'always',
                ],
            ],
        ]);
    }

    /**
     * @param int $userId
     * @param int $articleId
     *
     * @return array|EntityInterface|null
     */
    public function findUserLikeArticle($userId, $articleId)
    {
        return $this->find()
            ->where(['user_id' => $userId, 'article_id' => $articleId])
            ->first();
    }

    /**
     * @param int $userId
     *
     * @return ResultSetInterface
     */
    public function findUserLikes($userId)
    {
        return $this->find()
            ->where(['user_id' => $userId])
            ->all();
    }

    /**
     * @param int $articleId
     *
     * @return ResultSetInterface
     */
    public function findArticleLikes($articleId)
    {
        return $this->find()
            ->where(['article_id' => $articleId])
            ->all();
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['user_id', 'article_id']));
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['article_id'], 'Articles'));

        return $rules;
    }
}
