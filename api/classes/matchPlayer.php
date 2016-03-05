<?php
require_once 'DBUtils.php';

class MatchPlayer {

  private $DBUtils;

  public function __construct() {
    $this->DBUtils = new DBUtils();
  }

  function GetPlayerStats($matchId) {
    //TODO: Crear la sentencia
  }

  function GetConfirmedPlayers($matchId) {
    $sentence = "
      SELECT mp.*, p.*, p.name || ' ' || p.surname as fullName
      FROM MatchPlayer mp, Player p, Match m
      WHERE m.matchId = $matchId
      AND mp.matchId = m.matchId
      AND mp.playerId = p.playerId
      ORDER BY p.surname";

    return $this->DBUtils->QuerySelect($sentence);
  }

  function GetNotConfirmedPlayers($matchId) {
    $sentence = "
      SELECT DISTINCT p.*, (p.name || ' ' || p.surname) as fullName
      FROM Player p, Match m, SeasonPlayer sp, Season s, Team localTeam, Team visitorTeam
      WHERE m.matchId = $matchId
      AND p.playerId NOT IN (SELECT playerId FROM GetAllMatchPlayers WHERE matchId = m.matchId)
      AND p.playerId NOT IN (SELECT playerId FROM GetAllInjuredPlayers WHERE matchId = m.matchId)
      AND p.playerId = sp.playerId
      AND s.seasonId = sp.seasonId
      AND m.dateTime > s.beginDate
      AND m.dateTime < s.endDate
      AND m.dateTime > sp.incorporationDate
      ORDER BY p.surname";

    return $this->DBUtils->QuerySelect($sentence);
  }

  function GetInjuredPlayers($matchId) {
    $sentence = "
      SELECT ip.*, p.*, p.name || ' ' || p.surname as fullName
      FROM InjuredPlayer ip, Player p, Match m
      WHERE m.matchId = $matchId
      AND ip.matchId = m.matchId
      AND ip.playerId = p.playerId
      ORDER BY p.surname";

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
