<?php 
    namespace lbs\order\actions;
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;

    class CreateOrder {

        public function __invoke(Request $rq, Response $rs, $args): Response{
            $OrderRequestService = new \lbs\order\services\OrderRequestService();
            try{

                $body = $rq->getParsedBody();

                $items = array();

                foreach($body['items'] as $item){

                    $items[] = [
                        "uri" => $item["uri"],
                        'qty' => $item['q'],
                        'name' => $item['name'],
                        'price' => $item['price'],
                    ];

                }


                $data = [
                    'client_name' => $body['client_name'],
                    'client_mail' => $body['client_mail'],
                    'delivery' => [
                        'date' => $body['delivery']['date'],
                        'time' => $body['delivery']['time'],
                    ],
                    'items' => $items,
                ];

                $data = filter_var_array($data, FILTER_SANITIZE_ENCODED);



                $res = $OrderRequestService->createOrder($data);

                $rs = $rs->withStatus(201)
                    ->withHeader('Content-Type','application/json');
                $rs->getBody()->write(json_encode($res));

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