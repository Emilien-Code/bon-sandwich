<?php
declare(strict_types=1);
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use lbs\order\errors\exceptions as Exception;
use Slim\Factory\AppFactory;
use lbs\order\actions as Actions;

require_once __DIR__ . '/../vendor/autoload.php';
//$settings = require_once __DIR__ . '/settings.php';

$config = parse_ini_file("../conf/config.ini");

/* une instance de connexion  */
$db = new Illuminate\Database\Capsule\Manager();

$db->addConnection($config);
$db->setAsGlobal();           
$db->bootEloquent();         


$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
// $app->addErrorMiddleware(true, false, false);
$errorMiddleware = $app->addErrorMiddleware(true, false, false);
$errorMiddleware->getDefaultErrorHandler()->registerErrorRenderer('application/json', JsonErrorRenderer::class );

$app->get("/orders", Actions\GetOrders::class);
$app->post("/orders", Actions\CreateOrder::class);

$app->get("/orders/{id}", Actions\GetOrder::class);
$app->put('/orders/{id}', Actions\UpdateSandwichAction::class);
$app->get('/orders/{id}/items', Actions\GetOrderItemAction::class);
$app->post('/orders/{id}/payment',
 function (Request $rq, Response $rs, $args): Response {
    throw new Slim\Exception\HttpNotImplementedException($rq, "payment route not yet implemented");
 }
);

$app->run();
