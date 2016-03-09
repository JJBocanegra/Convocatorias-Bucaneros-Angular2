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
