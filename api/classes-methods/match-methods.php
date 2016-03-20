<?php
require_once 'classes/match.php';

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
?>
