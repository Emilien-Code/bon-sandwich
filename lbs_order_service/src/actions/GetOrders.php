<?php 
    namespace lbs\order\actions;
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;

    class GetOrders {

        public function __invoke(Request $rq, Response $rs, $args): Response{
            $OrderRequestService = new \lbs\order\services\OrderRequestService();
            try{

                $filter = !empty($_GET["c"]) ? $_GET["c"] : null;
                $sort = !empty($_GET["sort"]) ? $_GET["sort"] : null;
                $page = !empty($_GET["page"]) ? $_GET["page"] : 1;


                $orders = $OrderRequestService->getOrders($filter, $sort, $page);
                $count = $OrderRequestService->count();

                $formatedOrders = array();

                foreach ($orders as $order) {

                    $formatedOrders[] = [

                        'order' => $order,

                        "links" => [

                            "self" => ["href"=> "/orders/{$order["id"]}"]

                        ]
                   
                    ];

                }

                //Last page URI
                $pl = floor($count/15);
                $last = "/orders?page=".$pl;
                
                //next page URI
                $np = $page < $pl ? $page + 1 : $pl;
                $next = "/orders?page=" . $np;
                
                
                //previous page URI
                $pp = $page > 1 ? $page - 1 : 1;
                $prev = "/orders?page=".$pp ;
                
                
                
                
                
                $data = [
                    'type' => 'collection',
                    'count'=> $count,
                    'size'=> count($orders),
                    "links" => [
                        "next" => [ "href" => $np],
                        "prev" => [ "href" => $prev],
                        "last" => [ "href" => $last],
                        "first" => [ "href" => "/orders?page=1"],
                    ],
                    'orders' => $formatedOrders
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