<?php
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

require_once 'vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

$handler_function = function($e) use ($app) {
  echo $e;
};

ErrorHandler::register();
$exceptionHandler = ExceptionHandler::register();
$exceptionHandler->setHandler($handler_function);


//This hold all application controllers
$app['controllers']
->assert('playerId', '\d+')
->assert('matchId', '\d+')
->assert('seasonId', '\d+');

//Cause Angular send first an OPTIONS call, this should be used
$app->match("{url}", function($url) use ($app) {
  return "OK";
})->assert('url', '.*')->method("OPTIONS");

require_once 'classes-methods/player-methods.php';
require_once 'classes-methods/season-methods.php';
require_once 'classes-methods/match-methods.php';
require_once 'classes-methods/match-player-methods.php';

$app->error(function (\Exception $e, $code) {
  return new Response('ERROR: '.$e);
});

function getState($app, $result) {
  $httpCode = 200;

  if(!is_array($result)) {
    $httpCode = 400;
  }

  if(count($result) === 0) {
    $httpCode = 404;
  }

  return $app->json($result, $httpCode);
}

//run the app to handle the incoming request
//404, 405 responses are handled automatically
$app->run();
?>
