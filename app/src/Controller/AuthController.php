<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Response;
use Cake\Event\EventInterface;

class AuthController extends ApiController
{
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['login', 'logout']);
    }

    /**
     * This method logs in a user.
     *
     * @return Response|void|null
     */
    public function login()
    {
        $result = $this->Authentication->getResult();

        if ($result->isValid()) {
            return $this->successResponse(['message' => 'User logged in successfully']);
        } else {
            return $this->errorResponse('Invalid username or password', 401);
        }
    }

    /**
     * This method logs out a user.
     *
     * @return Response|void|null
     */
    public function logout()
    {
        $result = $this->Authentication->getResult();

        if ($result->isValid()) {
            $this->Authentication->logout();

            return $this->successResponse(['message' => 'User logged out successfully']);
        }
    }

    /**
     * This method returns a successful JSON response with a 200 status code.
     *
     * @param array $data
     * @param int $code
     *
     * @return Response
     */
    public function signup()
    {
        $userTable = $this->getTableLocator()->get('Users');
        $user = $userTable->newEntity($this->request->getData());

        if (!($failedValidationErrors = $user->getErrors()) && $userTable->save($user)) {
            return $this->successResponse(['message' => 'User signed up successfully']);
        }

        $errorMessages = !empty($failedValidationErrors) ? json_encode($failedValidationErrors) : 'User could not sign up';

        return $this->errorResponse($errorMessages);
    }
}
