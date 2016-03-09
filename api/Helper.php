<?php
class Helper {
  function IsNumber($value) {
    if ($value === null || $value === 'undefined' || !is_numeric($value)) {
      return false;
    }

    return true;
  }

  function ShowError($error) {
    echo 'Error: ', $error->getMessage(), "\n Location: ", $error->getFile(),' (', $error->getLine(), ')';
  }
}
?>
