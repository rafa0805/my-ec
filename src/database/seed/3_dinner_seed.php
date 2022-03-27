<?php
// use MyApp\Model\Lunch;
use MyApp\DB\PdoWrapper;

$db = new PdoWrapper();
// $lunch = new Lunch();

$db->newTable('dinners', [
  'id INT AUTO_INCREMENT',
  'date TIMESTAMP',
  'menu VARCHAR(255)',
  'member_id INT',
  'PRIMARY KEY (id)',
]);
