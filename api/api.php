<?php
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

require 'vendor/autoload.php';
require 'classes/player.php';
require 'classes/match.php';
require 'classes/matchPlayer.php';
require 'classes/season.php';

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

//Cause Angular send first an OPTIONS call, this should be used
$app->match("{url}", function($url) use ($app) {
  return "OK";
})->assert('url', '.*')->method("OPTIONS");

//********************PLAYER********************
$app['player'] = function($app) {
  return new Player();
};

$app->get('/players/{playerId}', function($playerId) use ($app) {
  $result = $app['player']->GetPlayerById($playerId);

  return getState($app, $result);
});

$app->post('/players', function(Request $request) use ($app) {
  $result = $app['player']->CreatePlayer($request->getContent());

  return getState($app, $result);
});

$app->put('/players/{playerId}', function(Request $request, $playerId) use ($app) {
  $result = $app['player']->UpdatePlayer($request->getContent(), $playerId);

  return getState($app, $result);
});


//********************SEASON********************
$app['season'] = function($app) {
  return new Season();
};

$app->post('/season/current/players/add/{playerId}', function($playerId) use ($app) {
  $result = $app['season']->AddPlayerToCurrentSeason($playerId);

  return getState($app, $result);
});

//TODO Separar estas dos funcionalidades en clases distintas
//********************MATCH********************
$app['match'] = function($app) {
  return new Match();
};

$app->get('/matches/', function() use ($app) {
  $result = $app['match']->GetMatches();

  return getState($app, $result);
});

$app->get('/matches/next', function() use ($app) {
  $result = $app['match']->GetNextMatch();

  return getState($app, $result);
});


//********************MATCH-PLAYER********************
$app['matchPlayer'] = function($app) {
  return new MatchPlayer();
};

$app->get('/matches/{matchId}/players/confirmed', function($matchId) use ($app) {
  $result = $app['matchPlayer']->GetConfirmedPlayers($matchId);

  return getState($app, $result);
});

$app->get('/matches/{matchId}/players/confirmed/add/{playerId}', function($matchId, $playerId) use ($app) {
  $result = $app['matchPlayer']->AddPlayer($matchId, $playerId);

  return getState($app, $result);
});

$app->get('/matches/{matchId}/players/confirmed/remove/{playerId}', function($matchId, $playerId) use ($app) {
  $result = $app['matchPlayer']->RemoveConfirmedPlayer($matchId, $playerId);

  return getState($app, $result);
});

$app->get('/matches/{matchId}/players/notConfirmed', function($matchId) use ($app) {
  $result = $app['matchPlayer']->GetNotConfirmedPlayers($matchId);

  return getState($app, $result);
});

$app->get('/matches/{matchId}/players/injured', function($matchId) use ($app) {
  $result = $app['matchPlayer']->GetInjuredPlayers($matchId);

  return getState($app, $result);
});

$app->get('/matches/{matchId}/players/injured/add/{playerId}', function($matchId, $playerId) use ($app) {
  $result = $app['matchPlayer']->AddInjuredPlayer($matchId, $playerId);

  return getState($app, $result);
});

$app->get('/matches/{matchId}/players/injured/remove/{playerId}', function($matchId, $playerId) use ($app) {
  $result = $app['matchPlayer']->RemoveInjuredPlayer($matchId, $playerId);

  return getState($app, $result);
});

//run the app to handle the incoming request
//404, 405 responses are handled automatically
$app->run();
?>
