<?php
require_once 'DBUtils.php';

class MatchPlayer {

  private $DBUtils;

  public function __construct() {
    $this->DBUtils = new DBUtils();
  }

  function GetConfirmedPlayers($matchId) {
    $sentence = "
      SELECT mp.*, p.*, p.name || ' ' || p.surname as fullName
      FROM MatchPlayer mp, Player p, Match m
      WHERE m.matchId = $matchId
      AND mp.matchId = m.matchId
      AND mp.playerId = p.playerId";

    return $this->DBUtils->QuerySelect($sentence);
  }

  function GetNotConfirmedPlayers($matchId) {
    //TODO Separar
    $month = (int)date('m');
    $year = date('Y');

    $season = "";

    if ($month >= 9) {
      $season = $year.'-'.(((int)$year)+1);
    } else {
      $season = (((int)$year)-1).'-'.$year;
    }

    $sentence = "
      SELECT p.*, p.name || ' ' || p.surname as fullName
      FROM Player p
      WHERE p.playerId IN (SELECT playerId From YearPlayer WHERE year = '$season')
      AND p.playerId NOT IN (SELECT playerId FROM MatchPlayer WHERE matchId = ".$matchId.")
      AND p.playerId NOT IN (SELECT playerId FROM InjuredPlayer WHERE matchId = ".$matchId.")
      AND p.playerId NOT IN (SELECT playerId FROM InactivePlayer)";

    return $this->DBUtils->QuerySelect($sentence);
  }

  function GetInjuredPlayers($matchId) {
    $sentence = "
      SELECT ip.*, p.*, p.name || ' ' || p.surname as fullName
      FROM InjuredPlayer ip, Player p, Match m
      WHERE m.matchId = $matchId
      AND ip.matchId = m.matchId
      AND ip.playerId = p.playerId";

    return $this->DBUtils->querySelect($sentence);
  }

  function AddPlayer($matchId, $playerName) {
    try {
      if (!is_numeric($matchId)) {
        throw new Exception("The argument \$matchId should be a number, actually is $matchId");
      }

      if (is_numeric($playerName)) {
        throw new Exception("The argument \$playerName should be a string, actually is $playerName");
      }

      $sentence = "
        INSERT INTO MatchPlayer
        ('matchId', 'playerId')
        VALUES($matchId,
          (SELECT playerId FROM Player WHERE name || ' ' || surname LIKE '$playerName'))";

      if ($this->DBUtils->Query($sentence) === 1) {
        return $this->GetPlayer($playerName);
      } else {
        throw new Exception("The player $playerName couldn't be added to the match $matchId. The SQL sentence was $sentence");
      }
    } catch (Exception $e) {
      return "Error: ".$e->getMessage();
    }
  }

  function AddInjuredPlayer($matchId, $playerName) {
    $sentence = "
      INSERT INTO InjuredPlayer
      ('matchId', 'playerId')
      VALUES($matchId,
      (SELECT playerId FROM Player WHERE name || ' ' || surname LIKE '$playerName'))";

    if ($this->DBUtils->Query($sentence) === 1) {
      return $this->GetPlayer($playerName);
    }
  }

  function GetPlayer($playerName) {
    $sentence = "
      SELECT *, name || ' ' || surname as fullName
      FROM Player
      WHERE name || ' ' || surname LIKE '$playerName'";

    return $this->DBUtils->QuerySelect($sentence);
  }
}
?>
