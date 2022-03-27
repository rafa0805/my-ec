<?php
namespace MyApp\Model;

use MyApp\DB\PdoWrapper;
use MyApp\DB\IDatabase;

abstract class Model {

  protected IDatabase $db;
  protected string $table;

  // todo: to eliminate dependency for mysql
  public function __construct()
  {
    $this->db = new PdoWrapper();
    if ($this->table === NULL) throw new \Exception('hoge');
  }

  public function create(array $data)
  {
    return $this->db->insert($this->table, $data);
  }

  public function update(array $data, array $where)
  {
    return $this->db->update($this->table, $data, $where);
  }

  public function get()
  {
    return $this->db->select($this->table, []);
  }

  public function delete(array $where)
  {
    return $this->db->delete($this->table, $where);
  }
}
