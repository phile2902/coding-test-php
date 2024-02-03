<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Response;

class ApiController extends AppController
{
    /**
     * This method returns a successful JSON response with a 200 status code.
     *
     * @param array $data
     * @param int $code
     *
     * @return Response
     */
    protected function successResponse(array $data = [], int $code = 200)
    {
        return $this->response->withStatus($code)
            ->withType('application/json')
            ->withStringBody(json_encode($data));
    }

    /**
     * This method returns an error JSON response with a 400 status code or expected error status code.
     *
     * @param string $message
     * @param int $code
     *
     * @return Response
     */
    protected function errorResponse(string $message, int $code = 400)
    {
        return $this->response->withStatus($code)
            ->withType('application/json')
            ->withStringBody(json_encode(['message' => $message]));
    }
}
