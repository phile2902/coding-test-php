<?php

declare(strict_types=1);

namespace App\Controller;

use Exception;
use Cake\Http\Response;

class ArticlesController extends ApiController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    /**
     * @return Response
     * @throws Exception
     */
    public function index()
    {
        $this->loadComponent('Paginator');
        $articles = $this->paginate($this->Articles->find());

        return $this->successResponse(['data' => $articles]);
    }

    /**
     * @param int $id
     *
     * @return Response
     * @throws Exception
     */
    public function view(int $id)
    {
        try {
            $article = $this->Articles->get($id);

            return $this->successResponse(['data' => $article]);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    private function injectUserToRequestPayload($data)
    {
        $data['user_id'] = $this->Authentication->get('id');

        return $data;
    }

    /**
     * @return Response
     * @throws Exception
     */
    public function add()
    {
        $data = $this->request->getData();
        $data = $this->injectUserToRequestPayload($data);
        $article = $this->Articles->newEntity($data);

        if (!empty($failedValidatorErrors = $article->getErrors()) && $this->Articles->save($article)) {
            return $this->successResponse(['data' => $article], 201);
        }

        $errorMessage = !empty($failedValidatorErrors) ? json_encode($failedValidatorErrors) : 'The article could not be saved. Please, try again.';

        return $this->errorResponse($errorMessage, 400);
    }

    private function isUserAuthorized($article)
    {
        return $article->user_id === $this->Authentication->get('id');
    }

    public function edit($id)
    {
        try {
            $article = $this->Articles->get($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }

        if (!$this->isUserAuthorized($article)) {
            return $this->errorResponse('You are not authorized to edit this article', 403);
        }

        $data = $this->request->getData();
        $article = $this->Articles->patchEntity($article, $data);

        if (!empty($failedValidatorErrors = $article->getErrors()) && $this->Articles->save($article)) {
            return $this->successResponse(['data' => $article]);
        }

        $errorMessage = !empty($failedValidatorErrors) ? json_encode($failedValidatorErrors) : 'The article could not be saved. Please, try again.';

        return $this->errorResponse($errorMessage);
    }

    public function delete($id)
    {
        try {
            $article = $this->Articles->get($id);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }

        if (!$this->isUserAuthorized($article)) {
            return $this->errorResponse('You are not authorized to delete this article', 403);
        }

        if ($this->Articles->delete($article)) {
            return $this->successResponse(['message' => 'Article deleted']);
        }

        return $this->errorResponse('The article could not be deleted. Please, try again.');
    }

    public function like()
    {
        $userId = $this->Authentication->get('id');
        $articleId = $this->request->getParam('article_id');
        $userArticleLikeTable = $this->getTableLocator()->get('UsersArticlesLikes');

        if ($userArticleLikeTable->exists(['user_id' => $userId, 'article_id' => $articleId])) {
            return $this->errorResponse('You have already liked this article', 400);
        }

        try {
            $article = $this->Articles->get($articleId);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }

        $userArticleLike = $userArticleLikeTable->newEntity([
            'user_id' => $userId,
            'article_id' => $articleId,
        ]);

        if (!empty($failedValidationErrors = $userArticleLike->getErrors()) && $userArticleLikeTable->save($userArticleLike)) {
            $totalLikes = $article->get('total_likes');
            $article->set('total_likes', ++$totalLikes);
            $this->Articles->save($article);

            return $this->successResponse(['message' => 'Article liked']);
        }

        $errorMessage = !empty($failedValidationErrors) ? json_encode($failedValidationErrors) : 'The article could not be liked. Please, try again.';

        return $this->errorResponse($errorMessage);
    }
}
