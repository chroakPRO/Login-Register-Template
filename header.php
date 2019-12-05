<?php
  /*Här startar vi session, som gör att vi kan spara information gällande om du är inloggade eller utloggad etc.
  */
  session_start();
  /* Här använder sätter vi bara in våran databas connection, så vi kan ansluta till databasen.
   Vi använder oss utav require istället för include så att om något skulle gå fel så,
   stoppas skriptet istälelt för att bara ge varning och sen fortsätta.
  */

  require "includes/dbh.inc.php";

?>


<!DOCTYPE html>
<html lang="en-us">
  <head>
    <meta charset="utf-8">
    <meta name="description" content="NTI">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>

    <header>
      <nav class="nav-header-main">
        <a class="header-logo" href="index.php">
          <img src="img/logo.png" alt="NTI LOGO">
        </a>
        <ul>
          <li><a href="index.php">Hem</a></li>
        </ul>
      </nav>
      <div class="header-login">
        <!--
       Här gör vi samma sak som vi gör i index,
       med hjälp av session kan vi bestämma om vi ska visa inloggning eller utloggning.
       Du kan också se att vi använder oss utav POST så man är lite säkrare när det kommer till,
        känslig information.
        -->
        <?php
    if (!isset($_SESSION['id'])) {
    echo '<form action="includes/login.inc.php" method="post">
		<input type="text" name="nameuser" placeholder="Username">  
        <input type="password" name="pwd" placeholder="Password">
        <button type="submit" name="login-submit">Login</button>
    </form>
    <a href="signup.php" class="header-signup">Signup</a>';
    }
    else if (isset($_SESSION['id'])) {
    echo '<form action="includes/logout.inc.php" method="post">
        <button type="submit" name="login-submit">Logout</button>
    </form>';
    }
    ?>
    </div>
    </header>
