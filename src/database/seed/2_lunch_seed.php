<?php
use MyApp\Model\Lunch;
use MyApp\DB\PdoWrapper;

$db = new PdoWrapper();
$lunch = new Lunch();

$db->newTable('lunches', [
  'id INT AUTO_INCREMENT',
  'date TIMESTAMP',
  'menu VARCHAR(255)',
  'member_id INT',
  'PRIMARY KEY (id)',
]);

$lunch->create([
  'member_id' => '2',
  'date' => '2022-03-20',
  'menu' => 'ramen',
]);
$lunch->create([
  'member_id' => '2',
  'date' => '2022-03-21',
  'menu' => 'yakiniku',
]);
$lunch->create([
  'member_id' => '2',
  'date' => '2022-03-22',
  'menu' => 'sushi',
]);


$lunch->create([
  'member_id' => '3',
  'date' => '2022-03-20',
  'menu' => 'soba',
]);
$lunch->create([
  'member_id' => '3',
  'date' => '2022-03-21',
  'menu' => 'pasta',
]);
$lunch->create([
  'member_id' => '3',
  'date' => '2022-03-22',
  'menu' => 'nabe',
]);

$lunch->create([
  'member_id' => '1',
  'date' => '2022-03-20',
  'menu' => 'okayu',
]);
$lunch->create([
  'member_id' => '1',
  'date' => '2022-03-21',
  'menu' => 'curry',
]);
$lunch->create([
  'member_id' => '1',
  'date' => '2022-03-22',
  'menu' => 'humburg',
]);
