<?php
require_once 'classes/matchPlayer.php';

$app['matchPlayer'] = function($app) {
  return new MatchPlayer();
};

$app->get('/matches/{matchId}/players/confirmed', function($matchId) use ($app) {
  $result = $app['matchPlayer']->GetConfirmedPlayers($matchId);

  return getState($app, $result);
});

$app->post('/matches/{matchId}/players/confirmed/add/{playerId}', function($matchId, $playerId) use ($app) {
  $result = $app['matchPlayer']->AddPlayer($matchId, $playerId);

  return getState($app, $result);
});

$app->delete('/matches/{matchId}/players/confirmed/remove/{playerId}', function($matchId, $playerId) use ($app) {
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

$app->post('/matches/{matchId}/players/injured/add/{playerId}', function($matchId, $playerId) use ($app) {
  $result = $app['matchPlayer']->AddInjuredPlayer($matchId, $playerId);

  return getState($app, $result);
});

$app->delete('/matches/{matchId}/players/injured/remove/{playerId}', function($matchId, $playerId) use ($app) {
  $result = $app['matchPlayer']->RemoveInjuredPlayer($matchId, $playerId);

  return getState($app, $result);
});
?>
