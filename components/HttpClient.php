<?php

use Src\TableGateways\PersonGateway;

class HttpClient
{
    private $requestMethod;

    public function __construct($requestMethod = null)
    {
        $this->requestMethod = $requestMethod;
    }

    public function successResponse($message, $data = null)
    {
        $response['status_code_header'] = 'HTTP/1.1 200 Success';
        $response['body'] = json_encode([
            'success' => true,
            'message' => $message,
            'data' => $data
        ]);
        return $response;
    }

    public function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'success' => false,
            'message' => 'Invalid input',
            'error_code' => 422,
            'data' => null
        ]);
        return $response;
    }

    public function unauthorizedResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 401 Unauthorized';
        $response['body'] = json_encode([
            'success' => false,
            'message' => 'Unauthorized',
            'error_code' => 401,
            'data' => null
        ]);
        return $response;
    }

    public function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = json_encode([
            'success' => false,
            'message' => 'Not found',
            'error_code' => 404,
            'data' => null
        ]);
        return $response;
    }
}
