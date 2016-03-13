<?php
require_once 'dbUtils.php';
require_once 'assertions.php';

class Player {

  private $DBUtils;
  private $Assertions;

  public function __construct() {
    $this->DBUtils = new DBUtils();
    $this->Assertions = new Assertions();
  }

  function GetPlayerById($playerId) {
    try {
      $this->Assertions->AssertIsNumber($playerId, 'playerId');

      $sentence = "
        SELECT *
        FROM Player
        WHERE playerId = :playerId";

      $parameters = array(':playerId' => $playerId);

      return $this->DBUtils->QuerySelect($sentence, $parameters);
    } catch (Exception $e) {
      return "Error: ".$e->getMessage();
    }
  }

  function UpdatePlayer($player, $playerId) {
    try {
      $sentence = "
        Update Player
        SET name = :name, firstSurname = :firstSurname, secondSurname = :secondSurname, nickname = :nickname, birthDate = :birthDate, hasImage = :hasImage
        WHERE playerId = :playerId";

      $playerData = json_decode($player, true);

      $parameters = array(
        ':playerId' => $playerId,
        ':name' => $playerData['name'],
        ':firstSurname' => $playerData['firstSurname'],
        ':secondSurname' => $playerData['secondSurname'],
        ':nickname' => $playerData['nickname'],
        ':birthDate' => $playerData['birthDate'],
        ':hasImage' => $playerData['hasImage'],
      );

      if ($this->DBUtils->Query($sentence, $parameters) === 1) {
        return $this->GetPlayerById($playerId);
      } else {
        throw new Exception("The player $playerId couldn't be updated. The SQL sentence was $sentence");
      }
    } catch (Exception $e) {
      return "Error: ".$e->getMessage();
    }
  }
}
?>
