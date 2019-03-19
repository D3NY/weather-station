<?php
error_reporting(0);
require ('Db.php');

$loginCredentials = parse_ini_file('config.ini');
Db::connect($loginCredentials['host'], $loginCredentials['database'], $loginCredentials['username'], $loginCredentials['password']);
$data = Db::queryAll('
		SELECT date, temperature, humidity, pressure, uv, altitude 
		FROM meteodb 
		ORDER BY id 
		DESC LIMIT 1
');

foreach($data as $d) {
	$date = date("D, d.m.Y", strtotime($d['date']));
	$temperature = round($d['temperature'], 1);
	$humidity = round($d['humidity'], 1);
	$pressure = round($d['pressure'], 1);
	$uv = round($d['uv'], 1);
	$altitude = round($d['altitude']);
}
?>