<?php
require_once 'DBUtils.php';

class Player {

  private $DBUtils;

  public function __construct() {
    $this->DBUtils = new DBUtils();
  }

  function GetPlayer($playerName) {
    $sentence = "
      SELECT *
      FROM Player
      WHERE name LIKE '".$playerName."'";

    return $this->DBUtils->QuerySelect($sentence);
  }
}
?>
