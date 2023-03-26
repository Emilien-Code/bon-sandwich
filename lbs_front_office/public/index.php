<?php
declare(strict_types=1);
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use lbs\front\errors\exceptions as Exception;
use Slim\Factory\AppFactory;
use lbs\front\actions as Actions;

require_once __DIR__ . '/../vendor/autoload.php';


$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, false, false);
$errorMiddleware->getDefaultErrorHandler()->registerErrorRenderer('application/json', JsonErrorRenderer::class );

$app->get("/orders", Actions\GetOrders::class);
$app->post("/signin",Actions\PostAuthAction::class);



$app->run();
