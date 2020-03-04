<?php

require 'header.php';
if (isset($_GET["click"])) {
	if ($_GET["click"] == "editsubmit") {

$profileid = $_GET['id'];
$sessionid = $_SESSION['id'];
$sessionuid = $_SESSION['uid'];
$sessiongroup = $_SESSION['group'];
 

if (isset($_SESSION['id'])) {
	if ($sessionuid == $profileid || $sessiongroup == "admin") {
	$profileid = $_GET['id'];
	$sql = "SELECT idUser, NameUser FROM users WHERE NameUser = ?";
	$stmt = mysqli_stmt_init($conn);

	if (!mysqli_stmt_prepare($stmt, $sql)) {
		header("Location: index.php?error=sqlerror");
		exit();
	} else {
		mysqli_stmt_bind_param($stmt, "s", $profileid);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_assoc($result);
	}
	?>
<?php
	echo "<form action='./includes/edit.inc2.php' method='post'>";
	echo "Here you can edit your username";
	echo "<div style=\"text-align: center; width: 100%;\">";
	echo "<div style=\"margin: 0 auto; width:100px; text-align:left;\">";
	echo "<br><br><br>";
	echo "</div>";
	echo "<div class='form-signup'>";
	echo "<input type='hidden' name='idUser' value=" . $row['idUser'] . "></td>";
	echo "Username: <input type='text' name='NameUser' value=" . $row['NameUser'] . "></td><br>";
	echo "<button type='submit' name='edit-submit'>Submit</button>";
	echo "</form>";
	echo "</div>";
}}}
else {
	header("Location: ./index.php");
	exit();
}



}
