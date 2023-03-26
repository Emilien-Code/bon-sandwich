<?php

namespace lbs\auth\actions;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class PostAuthAction{
  public function __invoke(Request $request, Response $response, $args): Response {
    $service = new \lbs\auth\services\AuthService();
    $headers = $request->getHeaders();
    
    if (!isset($headers['Authorization'][0])) {
      $response = $response->withStatus(401);

      $response->getBody()->write(json_encode([
          'error' => 'error',
          'code' => 401,
          'message' => 'Missing Authorization header'
        ]));
    }

    $connection = new \MongoDB\Client("mongodb://mongo.auth");
    $token = $service->signin($connection, $headers['Authorization'][0]);

    $response->getBody()->write(json_encode([
      "access-token" => "{$token['token']}",
      "refresh-token" => "{$token['refresh']}",
    ]));
    return $response;
  }

}