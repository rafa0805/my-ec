<?php
namespace MyApp\DB;

/**
 * TODO
 * - 実行履歴を残す
 * -
 */

// テーブル名やカラムはprepareのplaceholderを使うことができない。
// 下記参照 (記事の解決方法がよいかは別として)
// https://programmierfrage.com/items/how-can-i-fix-the-error-syntaxe-sql-with-delete-function
use MyApp\DB\DbConnectionFactory;
use MyApp\DB\IDatabase;

class PdoWrapper implements IDatabase {

  private \PDO $db;

  public function __construct()
  {
    $this->db = DbConnectionFactory::create('mysql');
  }

  public function newTable(string $name, array $arr_description)
  {
    $sql = 'CREATE TABLE IF NOT EXISTS __table_name__ __description__;';
    $description = '(' . implode(', ', $arr_description)  . ')';
    $sql = str_replace('__table_name__', $name, $sql);
    $sql = str_replace('__description__', $description, $sql);
    $stmt = $this->db->prepare($sql);

    try {
      $stmt->execute();
    } catch (\PDOException $e) {
      print('Error:'.$e->getMessage() . '[' . __LINE__ . ']' . PHP_EOL);
      die();
    }
  }

  public function insert(string $table, array $data):bool
  {
    $table = $this->sanitizeString($table);
    $data = $this->sanitizeArray($data);

    $arr_params = [];
    $arr_placeholders = [];
    $arr_values = [];
    foreach($data as $param => $value) {
      $arr_params[] = $param;
      $arr_placeholders[] = '?';
      $arr_values[] = $value;
    }
    $params = implode(', ', $arr_params);
    $placeholders = implode(', ', $arr_placeholders);

    $sql = "INSERT INTO {$table} ({$params}) values ({$placeholders});";

    $stmt = $this->db->prepare($sql);
    return $stmt->execute($arr_values);
  }

  public function update(string $table, array $data, array $where = []):bool
  {
    $table = $this->sanitizeString($table);
    $data = $this->sanitizeArray($data);

    var_dump($data);

    $sql = "UPDATE {$table} SET ";

    $arr_updateItems = [];
    $arr_values = [];
    foreach($data as $param => $value) {
      $arr_updateItems[] = "{$param}=?";
      $arr_values[] = $value;
    }
    $updateString = implode(', ', $arr_updateItems);
    $sql .= "{$updateString}";

    if (count($where) > 0) {
      $whereString = $this->makeWhereString($where);
      $sql .= " {$whereString}";
    }
    $sql .= ';';
    echo $sql.PHP_EOL;

    $stmt = $this->db->prepare($sql);
    return $stmt->execute($arr_values);
  }

  // public function select(string $table, array $where, string $order = 'ASC', int $limit = null)
  public function select(string $table, array $where):array
  {
    $table = $this->sanitizeString($table);

    $sql = "SELECT * FROM {$table};";

    $stmt = $this->db->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll();
  }

  public function leftJoin(string $table)
  {
    $table = $this->sanitizeString($table);
    // target table, parent key, child key, child id as different name, child key not required (in bellows case "mamber_id")

    $sql = "SELECT * FROM {$table} LEFT OUTER JOIN lunches ON members.id = lunches.member_id";

    $stmt = $this->db->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll();

  }



  public function delete(string $table, array $where = []):bool
  {
    $sql = "DELETE FROM {$table}";
    if (count($where) > 0) {
      $whereString = $this->makeWhereString($where);
      $sql .= " {$whereString}";
    }
    $sql .= ';';

    $stmt = $this->db->prepare($sql);
    return $stmt->execute();
  }

  /**
   * @param array $where param, value, [mode]
   */
  private function makeWhereString(array $where)
  {
    $arr = [];
    foreach($where as $item) {
      $param = $item[0];
      $value = $item[1];
      $mode = count($item) >= 3 ? $item[2] : '=';

      $arr[] = "{$param} {$mode} {$value}";
    }

    $whereString = 'WHERE ' . implode(' AND ', $arr);
    return $whereString;
  }

  private function sanitizeString(string $str)
  {
    return $str;
  }

  private function sanitizeArray(array $arr)
  {
    return $arr;
  }
}
