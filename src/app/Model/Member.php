<?php
namespace MyApp\Model;

class Member extends Model {

  protected string $table = 'members';

  public function get()
  {
    return $this->db->leftJoin($this->table);
  }
}
