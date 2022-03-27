<?php
namespace MyApp\DB;

class DbConnectionFactory {

  static function create(string $dbms_name)
  {
    $dns = $dbms_name . ':dbname=' . DBNAME . ';host=' . DBHOST . ':' . DBPORT;
    try {
      return new \PDO($dns, DBUSER, DBPASS);

    } catch (\PDOException $e) {
      echo $e->getMessage();
      exit;
    }
  }
}
