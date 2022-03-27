<?php
use MyApp\Model\Member;
use MyApp\DB\PdoWrapper;

$db = new PdoWrapper();
$member = new Member();

/**
 * migration
 */
$db->newTable('members', [
  'id INT AUTO_INCREMENT',
  'name VARCHAR(255)',
  'age INT',
  'PRIMARY KEY (id)',
]);

/**
 * seed
 */
$member->create([
  'name' => 'tatsuki',
  'age' => 25,
]);

$member->create([
  'name' => 'rafa',
  'age' => 25,
]);

$member->create([
  'name' => 'mami',
  'age' => 25,
]);

$member->create([
  'name' => 'tonki',
  'age' => 30,
]);
