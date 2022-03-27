<?php
define('DBHOST', 'mysql');
define('DBPORT', 3306);
define('DBNAME', 'mytest');
define('DBUSER', 'root');
define('DBPASS', 'example');
require(__DIR__ . '/vendor/autoload.php');

use MyApp\DB\PdoWrapper;
use MyApp\Model\Member;
use MyApp\Model\Lunch;

$db = new PdoWrapper();
$member = new Member();
$lunch = new Lunch();


$members = $member->get();
$lunches = $lunch->get();
echo '<pre>';
var_dump($members);
// var_dump($lunches);
echo '</pre>';
