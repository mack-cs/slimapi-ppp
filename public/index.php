<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
//use Slim\Exception\HttpNotFoundException;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/database.php';
// require($_SERVER["DOCUMENT_ROOT"]."/api/slimapi-ppp/vendor/autoload.php");

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);
$app->setBasePath("/api/slimapi-ppp/public");

$app->get('/', function(Request $request, Response $response){
    $response->getBody()->write("Hello Marko");
    return $response;
});

//Products Routes

require __DIR__ . '/../routes/products.php';

$app->run();