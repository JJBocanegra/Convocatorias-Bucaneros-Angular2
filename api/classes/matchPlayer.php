<?php
require_once 'dbUtils.php';
require_once 'classes/player.php';

class MatchPlayer {
  private $DBUtils;
  private $Player;

  public function __construct() {
    $this->DBUtils = new DBUtils();
    $this->Player = new Player();
  }

  function GetPlayerByIdStats($matchId) {
    //TODO: Crear la sentencia
  }

  function GetConfirmedPlayers($matchId) {
    $sentence = "
      SELECT p.*
      FROM MatchPlayer mp, Player p, Match m
      WHERE m.matchId = :matchId
      AND mp.matchId = m.matchId
      AND mp.playerId = p.playerId
      ORDER BY p.firstSurname";

    $parameters = array(
      ':matchId' => $matchId,
    );

    return $this->DBUtils->QuerySelect($sentence, $parameters);
  }

  function GetNotConfirmedPlayers($matchId) {
    $sentence = "
      SELECT DISTINCT p.*
      FROM Player p, Match m, SeasonPlayer sp, Season s, Team localTeam, Team visitorTeam
      WHERE m.matchId = :matchId
      AND p.playerId NOT IN (SELECT playerId FROM GetAllMatchPlayers WHERE matchId = m.matchId)
      AND p.playerId NOT IN (SELECT playerId FROM GetAllInjuredPlayers WHERE matchId = m.matchId)
      AND p.playerId = sp.playerId
      AND s.seasonId = sp.seasonId
      AND m.dateTime > s.beginDate
      AND m.dateTime < s.endDate
      AND m.dateTime > sp.incorporationDate
      ORDER BY p.firstSurname";

    $parameters = array(
      ':matchId' => $matchId,
    );

    return $this->DBUtils->QuerySelect($sentence, $parameters);
  }

  function GetInjuredPlayers($matchId) {
    $sentence = "
      SELECT p.*
      FROM InjuredPlayer ip, Player p, Match m
      WHERE m.matchId = :matchId
      AND ip.matchId = m.matchId
      AND ip.playerId = p.playerId
      ORDER BY p.firstSurname";

    $parameters = array(
      ':matchId' => $matchId,
    );

    return $this->DBUtils->QuerySelect($sentence, $parameters);
  }

  function AddPlayer($matchId, $playerId) {
    $sentence = "
      INSERT INTO MatchPlayer
      ('matchId', 'playerId')
      VALUES(:matchId, :playerId)";

    $parameters = array(
      ':matchId' => $matchId,
      ':playerId' => $playerId,
    );

    $query = $this->DBUtils->Query($sentence, $parameters);

    if ($query !== null && $query->rowCount() === 1) {
      return $this->Player->GetPlayerById($playerId);
    } else {
      throw new Exception("The player $playerId couldn't be added to the match $matchId. The SQL sentence was $sentence");
    }
  }

  function AddInjuredPlayer($matchId, $playerId) {
    $sentence = "
      INSERT INTO InjuredPlayer
      ('matchId', 'playerId')
      VALUES(:matchId, :playerId)";

    $parameters = array(
      ':matchId' => $matchId,
      ':playerId' => $playerId,
    );

    $query = $this->DBUtils->Query($sentence, $parameters);

    if ($query !== null && $query->rowCount() === 1) {
      return $this->Player->GetPlayerById($playerId);
    } else {
      throw new Exception("The player $playerId couldn't be added to injured players in match $matchId. The SQL sentence was $sentence");
    }
  }

  function RemoveInjuredPlayer($matchId, $playerId) {
    $sentence = "
      DELETE FROM InjuredPlayer
      WHERE matchId = :matchId
      AND playerId = :playerId";

    $parameters = array(
      ':matchId' => $matchId,
      ':playerId' => $playerId,
    );

    $query = $this->DBUtils->Query($sentence, $parameters);

    if ($query !== null && $query->rowCount() === 1) {
      return $this->Player->GetPlayerById($playerId);
    } else {
      throw new Exception("The player $playerId couldn't be removed from injured players in match $matchId. The SQL sentence was $sentence");
    }
  }

  function RemoveConfirmedPlayer($matchId, $playerId) {
    $sentence = "
      DELETE FROM MatchPlayer
      WHERE matchId = :matchId
      AND playerId = :playerId";

    $parameters = array(
      ':matchId' => $matchId,
      ':playerId' => $playerId,
    );

    $query = $this->DBUtils->Query($sentence, $parameters);

    if ($query !== null && $query->rowCount() === 1) {
      return $this->Player->GetPlayerById($playerId);
    } else {
      throw new Exception("The player $playerId couldn't be removed from confirmed players in match $matchId. The SQL sentence was $sentence");
    }
  }
}
?>
