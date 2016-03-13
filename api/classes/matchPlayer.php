<?php
require_once 'dbUtils.php';
require_once 'assertions.php';
require_once 'classes/player.php';

class MatchPlayer {

  private $DBUtils;
  private $Assertions;
  private $Player;

  public function __construct() {
    $this->DBUtils = new DBUtils();
    $this->Assertions = new Assertions();
    $this->Player = new Player();
  }

  function GetPlayerByIdStats($matchId) {
    //TODO: Crear la sentencia
  }

  function GetConfirmedPlayers($matchId) {
    try {
      $sentence = "
        SELECT p.*
        FROM MatchPlayer mp, Player p, Match m
        WHERE m.matchId = ?
        AND mp.matchId = m.matchId
        AND mp.playerId = p.playerId
        ORDER BY p.firstSurname";

      $parameters = array($matchId);

      return $this->DBUtils->QuerySelect($sentence, $parameters);
    } catch (Exception $e) {
      return "Error: ".$e->getMessage();
    }
  }

  function GetNotConfirmedPlayers($matchId) {
    try {
      $this->Assertions->AssertIsNumber($matchId, 'matchId');

      $sentence = "
        SELECT DISTINCT p.*
        FROM Player p, Match m, SeasonPlayer sp, Season s, Team localTeam, Team visitorTeam
        WHERE m.matchId = ?
        AND p.playerId NOT IN (SELECT playerId FROM GetAllMatchPlayers WHERE matchId = m.matchId)
        AND p.playerId NOT IN (SELECT playerId FROM GetAllInjuredPlayers WHERE matchId = m.matchId)
        AND p.playerId = sp.playerId
        AND s.seasonId = sp.seasonId
        AND m.dateTime > s.beginDate
        AND m.dateTime < s.endDate
        AND m.dateTime > sp.incorporationDate
        ORDER BY p.firstSurname";

      $parameters = array($matchId);

      return $this->DBUtils->QuerySelect($sentence, $parameters);
    } catch (Exception $e) {
      return "Error: ".$e->getMessage();
    }
  }

  function GetInjuredPlayers($matchId) {
    try {
      $this->Assertions->AssertIsNumber($matchId, 'matchId');

      $sentence = "
        SELECT p.*
        FROM InjuredPlayer ip, Player p, Match m
        WHERE m.matchId = ?
        AND ip.matchId = m.matchId
        AND ip.playerId = p.playerId
        ORDER BY p.firstSurname";

      $parameters = array($matchId);

      return $this->DBUtils->QuerySelect($sentence, $parameters);
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
        VALUES(?, ?)";

      $parameters = array($matchId, $playerId);

      if ($this->DBUtils->Query($sentence, $parameters) === 1) {
        return $this->Player->GetPlayerById($playerId);
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
        VALUES(?, ?)";

      $parameters = array($matchId, $playerId);

      if ($this->DBUtils->Query($sentence, $parameters) === 1) {
        return $this->Player->GetPlayerById($playerId);
      } else {
        throw new Exception("The player $playerId couldn't be added to injured players in match $matchId. The SQL sentence was $sentence");
      }
    } catch (Exception $e) {
      return "Error: ".$e->getMessage();
    }
  }

  function RemoveInjuredPlayer($matchId, $playerId) {
    try {
      $this->Assertions->AssertIsNumber($matchId, 'matchId');
      $this->Assertions->AssertIsNumber($playerId, 'playerId');

      $sentence = "
        DELETE FROM InjuredPlayer
        WHERE matchId = ?
        AND playerId = ?";

      $parameters = array($matchId, $playerId);

      if ($this->DBUtils->Query($sentence, $parameters) === 1) {
        return $this->Player->GetPlayerById($playerId);
      } else {
        throw new Exception("The player $playerId couldn't be removed from injured players in match $matchId. The SQL sentence was $sentence");
      }
    } catch (Exception $e) {
      return "Error: ".$e->getMessage();
    }
  }

  function RemoveConfirmedPlayer($matchId, $playerId) {
    try {
      $this->Assertions->AssertIsNumber($matchId, 'matchId');
      $this->Assertions->AssertIsNumber($playerId, 'playerId');

      $sentence = "
        DELETE FROM MatchPlayer
        WHERE matchId = ?
        AND playerId = ?";

      $parameters = array($matchId, $playerId);

      if ($this->DBUtils->Query($sentence, $parameters) === 1) {
        return $this->Player->GetPlayerById($playerId);
      } else {
        throw new Exception("The player $playerId couldn't be removed from confirmed players in match $matchId. The SQL sentence was $sentence");
      }
    } catch (Exception $e) {
      return "Error: ".$e->getMessage();
    }
  }
}
?>
