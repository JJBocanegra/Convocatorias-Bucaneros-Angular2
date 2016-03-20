<?php
require_once 'classes/season.php';

$app['season'] = function($app) {
  return new Season();
};

$app->post('/season/current/players/add/{playerId}', function($playerId) use ($app) {
  $result = $app['season']->AddPlayerToCurrentSeason($playerId);

  return getState($app, $result);
});
?>
