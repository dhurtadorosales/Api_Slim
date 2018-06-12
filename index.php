<?php
require_once 'vendor/autoload.php';

$app = new \Slim\Slim();

$app->get('/hello/:name', function($name) use ($app) {
    echo 'Hi ' . $name;
    var_dump($app->request->get('hola'));
})->name('hello');


function draftMiddle()
{
    echo 'Middleware';
}

function draftMiddleTwo()
{
    echo 'Middleware 2';
}

$app->get('/draft(/:one)(/:two)', 'draftMiddle', 'draftMiddleTwo', function ($one = null, $two = null) {
    echo $one . '<br>';
    echo $two . '<br>';
})->conditions(array(
    'one' => '[a-zA-Z]*',
    'two' => '[0-9]*'
));

$uri = '/slim/index.php/api/draft';

$app->group('/api', function () use ($app, $uri) {
    $app->group('/draft', function () use ($app, $uri) {

        $app->get('/hello/:name', function($name) {
            echo 'Hi ' . $name;
        });

        $app->get('/last-name/:lastName', function($lastName) {
            echo 'Your lastname is ' . $lastName;
        });

        $app->get('/redirect-hello/', function() use ($app, $uri) {
            //$app->redirect($uri . '/hello/diego');
            $app->redirect(
                $app->urlFor(
                    'hello',
                    array(
                        'name' => 'Diego'
                    )
                )
            );
        });

    });
});

$app->run();