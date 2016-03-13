<?php
class DBUtils {
  function Query($sentence, $parameters = array()) {
    try {
      $statement = $this->executeStatement($sentence, $parameters);

      return $statement->rowCount();
    } catch(Exception $e) {
      $this->showError($e);
    }
  }

  function QuerySelect($sentence, $parameters = array()) {
    try {
      $statement = $this->executeStatement($sentence, $parameters);

      return $statement->fetchAll(PDO::FETCH_ASSOC);
    } catch(Exception $e) {
      $this->showError($e);
    }
  }

  function executeStatement($sentence, $parameters) {
    $db = $this->createConnection();
    $statement = $db->prepare($sentence);
    $statement->execute($parameters);

    $this->CloseConnection($db);

    return $statement;
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
