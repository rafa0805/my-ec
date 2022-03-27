<?php
const ENDPOINT = 'mysql:dbname=mytest;host=mysql:3306';
const DATABASE = "mytest";
const USER = "root";
const PASS = "example";

try{
  $dbh = new PDO(ENDPOINT, USER, PASS);
}catch (PDOException $e){
  print('Error:'.$e->getMessage());
  die();
}

echo "OK";
