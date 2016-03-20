<?php
use Symfony\Component\HttpFoundation\Request;

require_once 'classes/player.php';

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
?>
