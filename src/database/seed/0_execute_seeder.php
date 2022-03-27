<?php
define('DBHOST', 'mysql');
define('DBPORT', 3306);
define('DBNAME', 'mytest');
define('DBUSER', 'root');
define('DBPASS', 'example');
require_once(__DIR__ . '/../../vendor/autoload.php');

// require_once(__DIR__ . '/1_members_seed.php');
// require_once(__DIR__ . '/2_lunch_seed.php');
require_once(__DIR__ . '/3_dinner_seed.php');
