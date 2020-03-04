<?php 
require 'header.php';

$sql = "SELECT idUser, NameUser FROM users";
$result = mysqli_query($conn, $sql);
$resultcheck = mysqli_num_rows($result);
if ($resultcheck > 0) {
    echo "<table class='table table-striped table-bordered table-sm'><tr><th>ID</th><th>Name</th></tr>";
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['idUser'] . "</td>";
        echo "<td><a href='profile-page.php?click=profilename&id=".$row['NameUser']."'> " .$row['NameUser'] ." </a></td>";
        echo "</tr>";
    }
}
else {
    echo "There are currently no profiles registered on this website!";
}
