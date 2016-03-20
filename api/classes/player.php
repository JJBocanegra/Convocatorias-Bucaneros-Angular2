<?php
require_once 'dbUtils.php';

class Player {

  private $DBUtils;

  public function __construct() {
    $this->DBUtils = new DBUtils();
  }

  function GetPlayerById($playerId) {
    $sentence = "
      SELECT *
      FROM Player
      WHERE playerId = :playerId";

    $parameters = array(':playerId' => $playerId);

    return $this->DBUtils->QuerySelect($sentence, $parameters);
  }

  function CreatePlayer($player) {
    $sentence = "
      INSERT INTO Player(name, firstSurname, secondSurname, nickname, birthDate, hasImage)
      VALUES (:name, :firstSurname, :secondSurname, :nickname, :birthDate, :hasImage)";

    $playerData = json_decode($player, true);

      $parameters = array();

    foreach ($playerData as $key => $value) {
      $parameters[$key] = $value;
    }

    $query = $this->DBUtils->Query($sentence, $parameters);

    if ($query !== null && $query->rowCount() === 1) {
      return $this->GetPlayerById($query->lastInsertId);
    } else {
      throw new Exception("The player couldn't be created. The SQL sentence was $sentence");
    }
  }

  function UpdatePlayer($player, $playerId) {
    $sentence = "
      UPDATE Player
      SET name = :name, firstSurname = :firstSurname, secondSurname = :secondSurname, nickname = :nickname, birthDate = :birthDate, hasImage = :hasImage
      WHERE playerId = :playerId";

    $playerData = json_dec2ode($player, true);

    $parameters = array(
      ':playerId' => $playerId,
      ':name' => $playerData['name'],
      ':firstSurname' => $playerData['firstSurname'],
      ':secondSurname' => $playerData['secondSurname'],
      ':nickname' => $playerData['nickname'],
      ':birthDate' => $playerData['birthDate'],
      ':hasImage' => $playerData['hasImage'],
    );

    if ($this->DBUtils->Query($sentence, $parameters)->rowCount() === 1) {
      return $this->GetPlayerById($playerId);
    } else {
      throw new Exception("The player $playerId couldn't be updated. The SQL sentence was $sentence");
    }
  }
}
?>
