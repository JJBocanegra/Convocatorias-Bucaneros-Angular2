<?php
require_once 'dbUtils.php';

class Match {
  private $DBUtils;

  public function __construct() {
    $this->DBUtils = new DBUtils();
  }

  function GetMatches() {
    $sentence = "
      SELECT m.matchId, m.datetime, m.state, local.name as localTeam, visitor.name as visitorTeam, local.localization, local.urlLocalization
      FROM Match m, Team local, Team visitor
      WHERE local.teamId = m.localTeamId
      AND visitor.teamId = m.visitorTeamId
      ORDER BY dateTime DESC";

    return $this->DBUtils->QuerySelect($sentence);
  }

  function GetMatchById($matchId) {
    $sentence = "
      SELECT m.matchId, m.datetime, m.state, local.name as localTeam, visitor.name as visitorTeam, local.localization, local.urlLocalization
      FROM Match m, Team local, Team visitor
      WHERE m.matchId = :matchId
      AND local.teamId = m.localTeamId
      AND visitor.teamId = m.visitorTeamId";

    $parameters = array(':matchId' => $matchId);

    return $this->DBUtils->QuerySelect($sentence, $parameters);
  }

  function GetNextMatch() {
    $sentence = "
      SELECT m.matchId, m.datetime, m.state, local.name as localTeam, visitor.name as visitorTeam, local.localization, local.urlLocalization
      FROM Match m, Team local, Team visitor
      WHERE local.teamId = m.localTeamId
      AND visitor.teamId = m.visitorTeamId
      AND m.dateTime > strftime('%Y/%m/%d %H:%M', 'now', 'localtime')
      AND (m.localTeamId = 1 OR m.visitorTeamId = 1)
      ORDER BY m.matchId DESC
      LIMIT 1";

    return $this->DBUtils->QuerySelect($sentence);
  }
}
?>
