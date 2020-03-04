<?php
require '../header.php';
$profileid = htmlspecialchars($_POST['NameUser']);
$id = $htmlspecialchars($_POST['idUser']);
$sessionid = $_SESSION['id'];
$sessionuid = $_SESSION['uid'];
$sessiongroup = $_SESSION['group'];


if (isset($_POST['edit-submit'])) {
	require "dbh.inc.php";

	if (empty($profileid)) {
		header("Location: ../edit.inc.php?error=emptyfields");
		exit();
	} else if (!preg_match("/^[a-zA-Z0-9]*$/", $profileid)) {
		header("Location: ../index.php?error=invalidusername");
		exit();
	}  else {
		$sql = "UPDATE users SET NameUser=? WHERE idUser=?;";
		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			header("Location:../index.php?error=sqlerror");
			exit();
		} else {
			mysqli_stmt_bind_param($stmt, "ss", $profileid ,$id);
			mysqli_stmt_execute($stmt);
			header("Location:../index.php?submit=success");
		}
	}
	if (mysqli_query($conn, $sql)) {
		mysqli_close($conn);
		header('Location: ../profile-page.php?edit=success'); 
	} else {
		echo "Error deleting record";
	}
}
else {
				header("Location: ../index.php");
				exit();
			}
