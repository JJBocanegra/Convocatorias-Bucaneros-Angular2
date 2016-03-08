<?php
class DBUtils {
  function Query($sentence) {
    $db = $this->createConnection();
    $result = $db->exec($sentence);

    return $result;
  }

  function QuerySelect($sentence) {
    $db = $this->createConnection();
    $result = $db->query($sentence, PDO::FETCH_ASSOC);

    $array = array();
    foreach ($result as $row) {
      array_push($array, $row);
    }

    return $array;
  }

  //TODO Mirar como cambiar la ruta de la BBDD
  function CreateConnection() {
    $db = new PDO("sqlite:../db.sqlite3");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $db;
  }
}
?>
