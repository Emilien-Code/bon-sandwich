<?php

namespace lbs\auth\actions;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ValidateAction{

  public function __invoke(Request $request, Response $response, $args): Response {
    $service = new \lbs\auth\services\AuthService();

    try{

      if ($request->getHeader('Authorization') === null)
        throw new \Exception("Missing Authorization header");
        
      $h = $request->getHeader('Authorization')[0];
        
      if (!isset($h)) 
        throw new \Exception("Missing Authorization header");
      
      
      $tokenstring = sscanf($h, "Bearer %s")[0] ;
      
      if (!isset($tokenstring)) 
        throw new \Exception("Missing Authorization header");

      
      
      
      $res = $service->verifyToken($tokenstring);
      
      $response->getBody()->write(json_encode($res));

      return $response;


    }catch(\Exception $e){

      $response = $response->withStatus(401);

      $response->getBody()->write(json_encode([
        'error' => 'error',
        'code' => 401,
        'message' => $e->getMessage()
      ]));

      return $response;

    }
  }

}