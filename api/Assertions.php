<?php
require_once 'helper.php';

class Assertions {
  private $Helper;

  public function __construct() {
    $this->Helper = new Helper();
  }

  function AssertIsNumber($value, $varName) {
    if (!$this->Helper->IsNumber($value)) {
      throw new Exception("$varName should be a number, actually is: $value");
    }
  }
}
?>
