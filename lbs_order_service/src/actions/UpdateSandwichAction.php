<?php 
    namespace lbs\order\actions;
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;

    class UpdateSandwichAction {

        public function __invoke(Request $rq, Response $rs, $args): Response{
            $OrderRequestService = new \lbs\order\services\OrderRequestService();
            try{
                $body = $rq->getParsedBody();
                
                if($body===null || $args["id"] === null)
                    throw new \Slim\Exception\HttpMethodNotAllowedException($rq, "bad request : body or param are empty");
                
            
                $keys = array_keys($body);
                $keys = array_diff($keys, ['mail', 'livraison', 'nom']);
                
                if(count($keys) != 0)
                  throw new \Slim\Exception\HttpMethodNotAllowedException($rq, "Bad request: unvalid fields");
            
                $data = array(
                    "livraison"=>$body["livraison"],
                    "mail"=>$body["mail"],
                    "nom"=>$body["nom"]
                );

                $data = filter_var_array($data, FILTER_SANITIZE_ENCODED);

                $order = $OrderRequestService->updateUser($data, $args["id"]);


                if($order===0)
                  throw new \Slim\Exception\HttpMethodNotAllowedException($rq, "Id not found");

                $rs->withStatus(204);
                
                return $rs;

            } catch( \Slim\Exception\HttpMethodNotAllowedException $e) {
                
                $error = [
                    "type"=>"error",
                    "error"=>$e->getCode(),
                    "message"=>$e->getMessage()
                ];
                
                $rs->getBody()->write(json_encode($error));
                $rs->withHeader('Content-Type','application/json')->withStatus($e->getCode());
                
                return $rs;

            }
        }

    }