<?php
class DBUtils {
  function Query($sentence, $parameters = array()) {
    try {
      $db = $this->createConnection();
      $result = $db->prepare($sentence);
      $result->execute($parameters);

      CloseConnection($db);

      return $result->rowCount();
    } catch(Exception $e) {
      $this->showError($e);
    }
  }

  function QuerySelect($sentence, $parameters = array()) {
    try {
      $db = $this->createConnection();
      $result = $db->prepare($sentence);
      $result->execute($parameters);

      CloseConnection($db);

      return $result->fetchAll(PDO::FETCH_ASSOC);
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

  function CloseConnection($db) {
    $db = null;
  }

  function showError($error) {
    echo 'Exception: ', $error->getMessage(), "\n Location: ", $error->getFile(),' (', $error->getLine(), ')';
  }
}
?>
