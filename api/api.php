<?php
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require 'vendor/autoload.php';
require 'classes/player.php';
require 'classes/match.php';
require 'classes/matchPlayer.php';

$app = new Silex\Application();
$app['debug'] = true;

function getState($app, $result) {
  if(!is_array($result)) {
    return $app->json($result, 400);
  }

  return $app->json($result, 200);
}

//Cause Angular send first and OPTIONS call, this should be used
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

$app->put('/players/{playerId}', function(Request $request, $playerId) use ($app) {
  $result = $app['player']->UpdatePlayer($request->getContent(), $playerId);

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
