<?php
error_reporting(0);
header('Content-Type: application/json');
require ('Db.php');

$loginCredentials = parse_ini_file('config.ini');
Db::connect($loginCredentials['host'], $loginCredentials['database'], $loginCredentials['username'], $loginCredentials['password']);
$data = Db::queryAll('
		SELECT time, temperature, humidity, pressure, uv, airquality FROM (
			SELECT * FROM meteodb ORDER BY id DESC LIMIT 288
		) sub
		ORDER BY id ASC
');
$array = array();
$i = 0;

foreach($data as $d) {
    if ($i++ % 6 == 0) {
        $array[] = $d;
    }
}

echo json_encode($array, JSON_PRETTY_PRINT);
?>