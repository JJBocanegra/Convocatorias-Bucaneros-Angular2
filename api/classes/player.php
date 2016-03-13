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
        WHERE playerId = ?";

        $parameters = array($playerId);

      return $this->DBUtils->QuerySelect($sentence, $parameters);
    } catch (Exception $e) {
      return "Error: ".$e->getMessage();
    }
  }
}
?>
