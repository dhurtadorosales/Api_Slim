<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
$method = $_SERVER['REQUEST_METHOD'];
if($method == "OPTIONS") {
    die();
}

require_once 'vendor/autoload.php';

$app = new \Slim\Slim();
$db = new mysqli(
    'localhost',
    'root',
    '****',
    'curso_slim'
);
mysqli_set_charset($db, 'utf8');

$app->get('/products', function() use ($db, $app) {
    $query = 'SELECT * FROM curso_slim.product;';
    $result = $db->query($query);

    $products = array();


    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    } else {
        echo '0 results';
    }

    echo json_encode($products);

});

$app->post('/products', function () use ($db, $app) {
    $query = "INSERT INTO curso_slim.product (id, name, description, price) VALUES (default, "
        . "'".  $app->request->post('name') . "', "
        . "'". $app->request->post('description') . "', "
        . "'". $app->request->post('price') . "'"
        . ");";

    $insert = $db->query($query);

    $result = array(
        'status' => 'true',
        'message' => 'Product has been created successfully'
    );

    if (!$insert) {
        $result = array(
            'status' => 'false',
            'message' => 'Error to create product'
        );
    }

    echo json_encode($result);
});

$app->put('/products/:id', function ($id) use ($db, $app) {
    $query = "UPDATE curso_slim.product  SET "
        . "name = '" . $app->request->post('name') .  "', "
        . "description = '" . $app->request->post('description') . "', "
        . "price = '" . $app->request->post('price') . "' "
        . " WHERE id = " . $id . ";";

    $update = $db->query($query);

    $result = array(
        'status' => 'true',
        'message' => 'Product has been updated successfully'
    );

    if (!$update) {
        $result = array(
            'status' => 'false',
            'message' => 'Error to update product'
        );
    }

    echo json_encode($result);
});

$app->delete('/products/:id', function ($id) use ($db, $app) {
    $query = "DELETE FROM curso_slim.product WHERE id = "
        . $id . ";";

    $delete = $db->query($query);

    $result = array(
        'status' => 'true',
        'message' => 'Product has been deleted successfully'
    );

    if (!$delete) {
        $result = array(
            'status' => 'false',
            'message' => 'Error to delete product'
        );
    }

    echo json_encode($result);
});

$app->run();