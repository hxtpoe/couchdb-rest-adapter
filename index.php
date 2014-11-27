<?php
require 'vendor/autoload.php';

use Guzzle\Http\Client;

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit();
}

const URL = 'http://localhost:5984/';

$app = new Phalcon\Mvc\Micro();
$di = new Phalcon\DI();

$di['client'] = new Client();

$app->get('/', function () use ($app, $di) {
    $result = $di['client']->get(URL)->send();

    $app->response->setStatusCode($result->getStatusCode(), '');
    $app->response->setContentType('application/json', 'utf-8');
    $app->response->setContent($result->getBody());

    return $app->response;
});

$app->get('/{db}/{key}', function ($db, $key) use ($app, $di) {
    $result = $di['client']->get(URL . $db . '/' . $key)->send();

    $app->response->setStatusCode($result->getStatusCode(), '');
    $app->response->setContentType('application/json', 'utf-8');
    $app->response->setContent($result->getBody());

    return $app->response;
});

$app->put('/{db}/{key}', function ($db, $key) use ($app, $di) {
    $result = $di['client']->put(URL . $db . '/' . $key, array('content-type' => 'application/json'))->setBody(file_get_contents('php://input'))->send();

    $app->response->setStatusCode($result->getStatusCode(), '');
    $app->response->setContentType('application/json', 'utf-8');
    $app->response->setContent($result->getBody());

    return $app->response;
});

$app->put('/{db}', function ($db) use ($app, $di) {
    $result = $di['client']->put(URL . $db, array('content-type' => 'application/json'))->send();

    $app->response->setStatusCode($result->getStatusCode(), '');
    $app->response->setContentType('application/json', 'utf-8');
    $app->response->setContent($result->getBody());

    return $app->response;
});

$app->post('/{db}/{key}', function ($db, $key) use ($app, $di) {
    $result = $di['client']->put(URL . $db . '/' . $key, array('content-type' => 'application/json'))->setBody(file_get_contents('php://input'))->send();

    $app->response->setStatusCode($result->getStatusCode(), '');
    $app->response->setContentType('application/json', 'utf-8');
    $app->response->setContent($result->getBody());

    return $app->response;
});

$app->delete('/{db}/{key}', function ($db, $key) use ($app, $di) {
    $result = $di['client']->put(URL . $db . '/' . $key, array('content-type' => 'application/json'))->send();

    $app->response->setStatusCode($result->getStatusCode(), '');
    $app->response->setContentType('application/json', 'utf-8');
    $app->response->setContent($result->getBody());

    return $app->response;
});

$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found");
    $app->response->setContentType('application/json', 'utf-8');

    return $app->response;
});

$app->handle();

