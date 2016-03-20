<?php
class DBUtils {
  function Query($sentence, $parameters = array()) {
      $statement = $this->executeStatement($sentence, $parameters);

      return $statement;
  }

  function QuerySelect($sentence, $parameters = array()) {
      $statement = $this->executeStatement($sentence, $parameters);

      return $statement->fetchAll(PDO::FETCH_ASSOC);
  }

  function executeStatement($sentence, $parameters) {
    $db = $this->createConnection();

    $statement = $db->prepare($sentence);

    $statement->execute($parameters);
    $statement->lastInsertId = $db->lastInsertId();

    $this->CloseConnection($db);

    return $statement;
  }

  //TODO Mirar como cambiar la ruta de la BBDD
  function CreateConnection() {
    $db = new PDO("sqlite:../db.sqlite3");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->exec('PRAGMA foreign_keys = ON;');

    return $db;
  }

  function CloseConnection($db) {
    $db = null;
  }
}
?>
