<?php 
    namespace lbs\order\actions;
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;

    class GetOrderItemAction {

        public function __invoke(Request $rq, Response $rs, $args): Response{
            $OrderRequestService = new \lbs\order\services\OrderRequestService();
            try{

                if($args["id"]===null)
                    throw new \Slim\Exception\HttpMethodNotAllowedException($rq, "bad request : id param is empty");

                $items = $OrderRequestService->getItems( $args["id"]);

                
                if($items===0)
                  throw new \Slim\Exception\HttpMethodNotAllowedException($rq, "Id not found");

                

                $data = array(
                    "type" => "collection",
                    "count" => count($items),
                    "items" => $items
                );

                $rs->withHeader('Content-Type','application/json')->withStatus(204);
                $rs->getBody()->write(json_encode($data));
                
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