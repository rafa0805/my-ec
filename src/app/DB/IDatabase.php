<?php declare(strict_types=1);
namespace MyApp\DB;

interface IDatabase {

  public function insert(string $table, array $data):bool;
  public function update(string $table, array $data, array $where):bool;
  public function delete(string $table, array $where):bool;
  public function select(string $table, array $where):array;

}
