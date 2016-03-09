<?php
class DBUtils {
  function Query($sentence) {
    try {
      $db = $this->createConnection();
      $result = $db->exec($sentence);

      return $result;
    } catch(Exception $e) {
      $this->showError($e);
    }
  }

  function QuerySelect($sentence) {
    try {
      $db = $this->createConnection();
      $result = $db->query($sentence, PDO::FETCH_ASSOC);

      $array = array();
      foreach ($result as $row) {
        array_push($array, $row);
      }

      return $array;
    } catch(Exception $e) {
      $this->showError($e);
    }
  }

  //TODO Mirar como cambiar la ruta de la BBDD
  function CreateConnection() {
    try {
      $db = new PDO("sqlite:../db.sqlite3");
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      return $db;
    } catch(Exception $e) {
      $this->showError($e);
    }
  }

  function showError($error) {
    echo 'Exception: ', $error->getMessage(), "\n Location: ", $error->getFile(),' (', $error->getLine(), ')';
  }
}
?>
