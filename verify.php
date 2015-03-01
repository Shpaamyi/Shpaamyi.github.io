<?php

// Verification script by Snuffleupagus

define('DB_HOST', 'LOCALHOST');
define('DB_USER', 'root');
define('DB_PASS', 'botmaker');
define('DB_NAME', 'rotmg');

function init_db() {
	try {
		$pdo = new PDO( 'mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS );
		return $pdo;
	} catch ( PDOException $e ) {
		error_log( "Failed to connect to database: " . $e->getMessage() );
		die("No database connection.");
	}
}

$pdo = init_db();

if (@$_GET['email'] && @$_GET['key']) {
	$q = $pdo->query("SELECT * FROM `emails` WHERE `email` = ".$pdo->quote($_GET['email'])." AND `accessKey` = ".$pdo->quote($_GET['key']) );
	if (!$q) {
		error_log( "Error fetching record from `emails` table: " . $e->getMessage() );
		die("Database error.");
	}

	$row = $q->fetch();

	if ($row) {
		$q = $pdo->exec("UPDATE `accounts` SET verified = 1 WHERE `email` = ".$pdo->quote($row['email']));
		if ($q == 1) {
			echo "Account successfully verified.";
		} else if ($q == 0) {
			echo "Account already verified.";
		}
	} else {
		echo "Account validation failed";
	}
}