<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Exception\HttpNotFoundException;

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);
$app->setBasePath("/api/slimapi-ppp/public");

$app->get('/products/all', function(Request $request, Response $response){
    $sql = "SELECT * FROM product_dimens";

    try{
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->query($sql);
        $products = $stmt->fetchAll(PDO::FETCH_OBJ);

        $db = null;
        $response->getBody()->write(json_encode($products));

        return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);

    }catch(PDOException $e){
        $error = array(
            "message"=> $e->getMessage()
        );
        $response->getBody()->write(json_encode($error));
        return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(500);
    }
});

$app->get('/products/{barcode}', function(Request $request, Response $response, array $args){
    $barcode = $args['barcode'];
    $sql = "SELECT * FROM product_dimens WHERE barcode = $barcode";

    try{
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->query($sql);
        $product = $stmt->fetch(PDO::FETCH_OBJ);

        $db = null;
        $response->getBody()->write(json_encode($product));

        return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);

    }catch(PDOException $e){
        $error = array(
            "message"=> $e->getMessage()
        );
        $response->getBody()->write(json_encode($error));
        return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(500);
    }
});


$app->delete('/products/delete/{barcode}', function(Request $request, Response $response, array $args){
    $barcode = $args['barcode'];
    $sql = "DELETE FROM product_dimens WHERE barcode = $barcode";

    try{
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->query($sql);
        $result = $stmt->execute();
        $db = null;
        $response->getBody()->write(json_encode($result));

        return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);

    }catch(PDOException $e){
        $error = array(
            "message"=> $e->getMessage()
        );
        $response->getBody()->write(json_encode($error));
        return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(500);
    }
});

$app->post('/products/add', function(Request $request, Response $response){
    $data = $request->getParsedBody();
    $barcode = $data['barcode'];
    $length = $data['length'];
    $width = $data['width'];
    $height = $data['height'];
    $sql = "INSERT INTO product_dimens (barcode, length, width, height) VALUES (:barcode, :length, :width, :height)";

    try{
        $db = new DB();
        $conn = $db->connect();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":barcode", $barcode, PDO::PARAM_STR);
        $stmt->bindParam(":length", $length, PDO::PARAM_STR);
        $stmt->bindParam(":width", $width, PDO::PARAM_STR);
        $stmt->bindParam(":height", $height, PDO::PARAM_STR);

        $result = $stmt->execute();

        $db = null;
        $response->getBody()->write(json_encode($result));

        return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(200);

    }catch(PDOException $e){
        $error = array(
            "message"=> $e->getMessage()
        );
        $response->getBody()->write(json_encode($error));
        return $response
        ->withHeader('content-type', 'application/json')
        ->withStatus(500);
    }
});


// $app->delete('/products/delete/{barcode}', function(Request $request, Response $response, array $args){
//     $barcode = $args['barcode'];
//     $sql = "DELETE FROM product_dimens WHERE barcode = $barcode";

//     try{
//         $db = new DB();
//         $conn = $db->connect();

//         $stmt = $conn->prepare($sql);
//         // $stmt->bindParam(":barcode", $barcode, PDO::PARAM_STR);

//         $result = $stmt->execute();

//         $db = null;
//         $response->getBody()->write(json_encode($result));

//         return $response
//         ->withHeader('content-type', 'application/json')
//         ->withStatus(200);

//     }catch(PDOException $e){
//         $error = array(
//             "message"=> $e->getMessage()
//         );
//         $response->getBody()->write(json_encode($error));
//         return $response
//         ->withHeader('content-type', 'application/json')
//         ->withStatus(500);
//     }
// });