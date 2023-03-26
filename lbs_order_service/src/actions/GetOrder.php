<?php 
    namespace lbs\order\actions;
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;

    class GetOrder {

        public function __invoke(Request $rq, Response $rs, $args): Response{
            $OrderRequestService = new \lbs\order\services\OrderRequestService();
            try{

                    $order = $OrderRequestService->getOrder($args["id"]);
                    
                    //if($args["id"]===null || order === null){
                    //    return $rs->withStatus(501);
                    //}
                
                    $items = $OrderRequestService->getItems( $args["id"]);
                
                
                    $data = [
                                'type' => 'resource',
                                'order' => [
                                    "id" => $order["id"],
                                    "client_mail" => $order["nom"],
                                    "client_name" => $order["mail"],
                                    "order_date" => $order["created_at"],
                                    "delivery_date" => (float)$order["livraison"],
                                    "total_amount" => $order["montant"]
                                ],
                                'items' => $items,
                                "links" => [
                                    "items" => ["href"=> "/orders/{$args["id"]}/items/"],
                                    "self" => ["href"=> "/orders/{$args["id"]}"]
                                ]
                            ];
                    
                    
                    $rs = $rs->withStatus(201)
                             ->withHeader('Content-Type','application/json');
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