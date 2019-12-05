<?php
session_start();  /*
  Här startar vi session för att försäkra oss om att du faktiskt blir utloggad.
  Låt säga att session inte är startad så kommer inte session kunna bli förstörd.
  */
session_unset(); /*
 Det här använder för att uppgöra alla sessionser som är aktiva, vi stänger asså av sessioner.
 */
session_destroy(); /*
 Här förstör vi all session data, så att ingenting ska sparas.
 Det tar alltså bort alltid som har med din session att göra.
 */
header("Location: ../index.php");
