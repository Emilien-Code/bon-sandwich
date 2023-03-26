<?php
declare(strict_types=1);
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use lbs\order\errors\exceptions as Exception;
use Slim\Factory\AppFactory;
use lbs\order\actions as Actions;
use MongoDB\Client as Client;


require_once __DIR__ . '/../vendor/autoload.php';

$conection = new Client("mongodb://mongo.auth");


// var_dump($conection->users->authLbs->find());
// var_dump($conection->users->authLbs->find());



$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
// $app->addErrorMiddleware(true, false, false);
$errorMiddleware = $app->addErrorMiddleware(true, false, false);
$errorMiddleware->getDefaultErrorHandler()->registerErrorRenderer('application/json', JsonErrorRenderer::class );

$app->post("/signin",lbs\auth\actions\PostAuthAction::class);
$app->get("/validate",lbs\auth\actions\ValidateAction::class);

$app->run();
