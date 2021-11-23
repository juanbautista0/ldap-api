<?php

/*
|--------------------------------------------------------------------------
| Application settings
|--------------------------------------------------------------------------
|
| (EN) Here are the application settings
| (ES) Aquí se incluyen las configuraciones de la aplicacion
|
*/
require '../config.php';

$app = new FrameworkX\App;

$app->get('/', fn () => new React\Http\Message\Response(200, [], json_encode(['code' => 200, 'data' => ['message' => 'API LDAP authentication']])));

$app->post('/v1/auth', function (Psr\Http\Message\ServerRequestInterface $request) {
    $auth =  new Modules\V1\Controllers\Auth;
    return $auth->login($request);
});

$app->run();
