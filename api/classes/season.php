<?php
require_once 'dbUtils.php';

class Season {
  private $DBUtils;

  public function __construct() {
    $this->DBUtils = new DBUtils();
  }

  function GetCurrentSeason() {
    $sentence = "
      SELECT seasonId
      FROM GetCurrentSeason";

    return $this->DBUtils->QuerySelect($sentence);
  }

  function GetPlayerInSeason($playerId, $seasonId) {
    $sentence = "
      SELECT *
      FROM SeasonPlayer
      WHERE playerId = :playerId
      AND seasonId = :seasonId";

      $parameters = array(
        ':seasonId' => $seasonId,
        ':playerId' => $playerId,
      );

    return $this->DBUtils->QuerySelect($sentence, $parameters);
  }

  function AddPlayerToCurrentSeason($playerId) {
    $seasonId = $this->GetCurrentSeason()[0]['seasonId'];
    $incorporationDate = date('Y-m-d');

    $sentence = "
      INSERT INTO SeasonPlayer(seasonId, playerId, incorporationDate)
      VALUES (:seasonId, :playerId, :incorporationDate)";

    $parameters = array(
      ':seasonId' => $seasonId,
      ':playerId' => $playerId,
      ':incorporationDate' => $incorporationDate,
    );

    $query = $this->DBUtils->Query($sentence, $parameters);

    if ($query !== null && $query->rowCount() === 1) {
      return $this->GetPlayerInSeason($playerId, $seasonId);
    } else {
      throw new Exception("The player couldn't be added to the current season. The SQL sentence was $sentence");
    }
  }
}
?>
