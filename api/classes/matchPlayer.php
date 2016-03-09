<?php
require_once 'DBUtils.php';
require_once 'Assertions.php';

class MatchPlayer {

  private $DBUtils;
  private $Assertions;

  public function __construct() {
    $this->DBUtils = new DBUtils();
    $this->Assertions = new Assertions();
  }

  function GetPlayerStats($matchId) {
    //TODO: Crear la sentencia
  }

  function GetConfirmedPlayers($matchId) {
    $sentence = "
      SELECT mp.*, p.*
      FROM MatchPlayer mp, Player p, Match m
      WHERE m.matchId = $matchId
      AND mp.matchId = m.matchId
      AND mp.playerId = p.playerId
      ORDER BY p.firstSurname";

    return $this->DBUtils->QuerySelect($sentence);
  }

  function GetNotConfirmedPlayers($matchId) {
    $this->Assertions->AssertIsNumber($matchId, 'matchId');

    $sentence = "
      SELECT DISTINCT p.* FROM Player p, Match m, SeasonPlayer sp, Season s, Team localTeam, Team visitorTeam
      WHERE m.matchId = $matchId
      AND p.playerId NOT IN (SELECT playerId FROM GetAllMatchPlayers WHERE matchId = m.matchId)
      AND p.playerId NOT IN (SELECT playerId FROM GetAllInjuredPlayers WHERE matchId = m.matchId)
      AND p.playerId = sp.playerId
      AND s.seasonId = sp.seasonId
      AND m.dateTime > s.beginDate
      AND m.dateTime < s.endDate
      AND m.dateTime > sp.incorporationDate
      ORDER BY p.firstSurname";

    return $this->DBUtils->QuerySelect($sentence);
  }

  function GetInjuredPlayers($matchId) {
    try {
      $this->Assertions->AssertIsNumber($matchId, 'matchId');

      $sentence = "
        SELECT ip.*, p.*
        FROM InjuredPlayer ip, Player p, Match m
        WHERE m.matchId = $matchId
        AND ip.matchId = m.matchId
        AND ip.playerId = p.playerId
        ORDER BY p.firstSurname";

      return $this->DBUtils->querySelect($sentence);
    } catch (Exception $e) {
      return "Error: ".$e->getMessage();
    }
  }

  function AddPlayer($matchId, $playerId) {
    try {
      $this->Assertions->AssertIsNumber($matchId, 'matchId');
      $this->Assertions->AssertIsNumber($playerId, 'playerId');

      $sentence = "
        INSERT INTO MatchPlayer
        ('matchId', 'playerId')
        VALUES($matchId, $playerId)";

      if ($this->DBUtils->Query($sentence) === 1) {
        return $this->GetPlayer($playerId);
      } else {
        throw new Exception("The player $playerId couldn't be added to the match $matchId. The SQL sentence was $sentence");
      }
    } catch (Exception $e) {
      return "Error: ".$e->getMessage();
    }
  }

  function AddInjuredPlayer($matchId, $playerId) {
    try {
      $this->Assertions->AssertIsNumber($matchId, 'matchId');
      $this->Assertions->AssertIsNumber($playerId, 'playerId');

      $sentence = "
        INSERT INTO InjuredPlayer
        ('matchId', 'playerId')
        VALUES($matchId, $playerId)";

      if ($this->DBUtils->Query($sentence) === 1) {
        return $this->GetPlayer($playerId);
      } else {
        throw new Exception("The player $playerId couldn't be added to injured players in match $matchId. The SQL sentence was $sentence");
      }
    } catch (Exception $e) {
      return "Error: ".$e->getMessage();
    }
  }

  function GetPlayer($playerId) {
    try {
      $this->Assertions->AssertIsNumber($playerId, 'playerId');

      $sentence = "
        SELECT *
        FROM Player
        WHERE playerId = $playerId";

      return $this->DBUtils->QuerySelect($sentence);
    } catch (Exception $e) {
      return "Error: ".$e->getMessage();
    }
  }
}
?>
