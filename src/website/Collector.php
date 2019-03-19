<?php
error_reporting(0);
require ('Db.php');

$loginCredentials = parse_ini_file('config.ini');
Db::connect($loginCredentials['host'], $loginCredentials['database'], $loginCredentials['username'], $loginCredentials['password']);
date_default_timezone_set('Europe/Prague');
$date = date("Y.m.d");
$time = date("H:i:s");
$postData = array(
	'key',
	'temperature',
	'humidity',
	'pressure',
	'uv',
	'ir',
	'altitude',
	'airquality'
);

foreach($postData as $field) {
	if (empty($_POST[$field])) {
		$error = $date . " " . $time . " All fields are required!" . PHP_EOL;
		file_put_contents("error_log.txt", $error, FILE_APPEND);
	}
	else {
		$espKey = $_POST['key'];
		$temperature = $_POST['temperature'];
		$humidity = $_POST['humidity'];
		$pressure = $_POST['pressure'];
		$uv = $_POST['uv'];
		$ir = $_POST['ir'];
		$altitude = $_POST['altitude'];
		$airquality = $_POST['airquality'];
		if ($loginCredentials['key'] != $espKey) {
			$error = $date . " " . $time . " Verification keys does not match!" . PHP_EOL;
			file_put_contents("error_log.txt", $error, FILE_APPEND);
		}
		else {
			Db::query('INSERT INTO meteodb (date, time, temperature, humidity, pressure, uv, ir, altitude, airquality)
				   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)', $date, $time, $temperature, $humidity, $pressure, $uv, $ir, $altitude, $airquality);
			exit();
		}
	}
}
?>