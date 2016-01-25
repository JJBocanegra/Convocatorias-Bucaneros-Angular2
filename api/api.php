<?php
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require 'vendor/autoload.php';
require 'classes/match.php';
require 'classes/matchPlayer.php';

foreach ($_GET as $key=>$val) {
  echo $key." = ".$val;
}

$app = new Silex\Application();
$app['debug'] = true;

function getState($app, $result) {
  if(!is_array($result)) {
    return $app->json($result, 400);
  }

  return $app->json($result, 200);
}

//TODO Separar estas dos funcionalidades en clases distintas
//********************MATCH********************
$app['match'] = function($app) {
  return new Match();
};

$app->get('/matches/', function() use ($app) {
  $result = $app['match']->GetMatches();

  return getState($app, $result);
});

$app->get('/matches/last', function() use ($app) {
  $result = $app['match']->GetLastMatch();

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

$app->get('/matches/{matchId}/players/confirmed/add/{playerName}', function($matchId, $playerName) use ($app) {
  $result = $app['matchPlayer']->AddPlayer($matchId, $playerName);

  return getState($app, $result);
});

$app->get('/matches/{matchId}/players/confirmed/remove/{playerName}', function($matchId, $playerName) use ($app) {
  $result = $app['matchPlayer']->RemovePlayerFromMatch($matchId, $playerName);

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

$app->get('/matches/{matchId}/players/injured/add/{playerName}', function($matchId, $playerName) use ($app) {
  $result = $app['matchPlayer']->AddInjuredPlayer($matchId, $playerName);

  return getState($app, $result);
});

$app->get('/matches/{matchId}/players/injured/remove/{playerName}', function($matchId, $playerName) use ($app) {
  $result = $app['matchPlayer']->RemoveInjuredPlayer($matchId, $playerName);

  return getState($app, $result);
});

$app->get('/players/{playerName}', function($playerName) use ($app) {
  $result = $app['matchPlayer']->GetPlayer($playerName);

  return getState($app, $result);
});

//run the app to handle the incoming request
//404, 405 responses are handled automatically
$app->run();
?>