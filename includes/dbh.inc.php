<?php


require '/var/www/html/apps/appenv.php';

$dBServername = "localhost";
$dBUsername = "root";
$dBPassword = "$password";
$dBName = "apps";

// skapar anslutningen
$conn = mysqli_connect($dBServername, $dBUsername, $dBPassword, $dBName);

// Checkar anslutningen
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
	/*
	  Här är inget speciellt, vi skapar en var för att kunna ansluta till databasen oberoende på,
	vilken fil du är, det ändå du behvöer göra är att skriva "include dbh.inc.php", i toppen va varje sida.
	Du kan sedan enkelt använda dig utav $conn för att ansluta till databasen.

	 */
}
