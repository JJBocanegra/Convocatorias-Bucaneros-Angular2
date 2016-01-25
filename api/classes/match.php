<?php
require_once 'DBUtils.php';

class Match {

  private $DBUtils;

  public function __construct() {
    $this->DBUtils = new DBUtils();
  }

  function GetMatches() {
    $sentence = "
      SELECT m.matchId, m.datetime, m.state, local.name as local, visitor.name as visitor, local.localization, local.urlLocalization
      FROM Match m, Team local, Team visitor
      WHERE local.teamId = m.localTeamId
      AND visitor.teamId = m.visitorTeamId";

    return $this->DBUtils->QuerySelect($sentence);
  }

  function GetLastMatch() {
    $sentence = "
      SELECT m.matchId, m.datetime, m.state, local.name as local, visitor.name as visitor, local.localization, local.urlLocalization
      FROM Match m, Team local, Team visitor
      WHERE local.teamId = m.localTeamId
      AND visitor.teamId = m.visitorTeamId
      ORDER BY m.matchId DESC
      LIMIT 1";

    return $this->DBUtils->QuerySelect($sentence);
  }
}
?>
