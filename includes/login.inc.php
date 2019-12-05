<?php
/* Här har vi en av säkerhetsåtgärderna som jag skriver om.
Allting i dokumentet går under detta if statement, så om man inte har tryckt på knappen,
'login-submit' Så kommer ingen kod att köras förutom det som står i else längst ner.
Vilket kommer att skicka tillbaka en till index.php  */
if (isset($_POST['login-submit'])) {


	/*
	 Här har vi ett exempel på det jag skriver i dbh.inc.php. Jag inkluderar require 'dbh.inc.php'
    Så vi kan lättare ha åtkomst till databasen genom $conn.
	 */
  require 'dbh.inc.php';

	// Här hämtar vi information från signup.php, så vi kan använda den.
  $nameuser = $_POST['nameuser'];
  $password = $_POST['pwd'];


	/*
   *
  Det första man alltid ska göra innan man börjar köra den huvudsakliga koden är att leta efter fel.
  Det vi gör här är att vi sätter lite regler och parametrar för vad de får ha för lösenord etc.
  Här är också där vi skickar tillbaka information, låt säga att ditt lösenord,
  vill vi inte att användaren ska behöva skriva in användarnamnet, så vi skickar tillbaka användarnamnet till formen.
  Så användaren inte behöver skriva in användarnamnet igen.

  */
	/*
	  Här tittar vi om alla fält är ifyllda.
	  */
  if (empty($nameuser) || empty($password)) {
    header("Location: ../index.php?error=emptyfields&nameuser=".$nameuser);
    exit();
  }
  else {


 // Det är samma sak här som i signup, vi gör en template och sen med prepared statements.
    $sql = "SELECT * FROM users WHERE nameUser=?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
		// Fungerar inte initationen eller att det inte går att ansluta till databasen så skickar vi felet error=sqlerror
		header("Location: ../index.php?error=sqlerror");
      exit();
    }
    else {


      mysqli_stmt_bind_param($stmt, "s", $nameuser);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      if ($row = mysqli_fetch_assoc($result)) {
      	/* Här hämtar man lösenordet, vi ser ju inte lösenordet i databasen eftersom vi,använde oss av password_hash
		  Vi använder sedan password_verify för att titta om lösenord de skrev in matchar det hashade som ligger i
		  databasen. */
        $pwdCheck = password_verify($password, $row['PasswordUser']);
        if ($pwdCheck == false) {
          header("Location: ../index.php?error=wrongpwd");
          exit();
        }
        // Om det det är rätt, så kommer vi starta en session med hjälp av session_start!
        else if ($pwdCheck == true) {
          session_start();
          /*Vi  skapar sedan session id, för att kunna bestämma vem du är etc, detta kan man sedan använda för att
			skapa profil etc. */
          $_SESSION['id'] = $row['idUser'];
          $_SESSION['uid'] = $row['nameUser'];

          // Vi skickar sedan tillbaka dom till framsidan.
          header("Location: ../index.php?login=success");
          exit();
        }
      }
      else {
      	// Det här använder vi om användarnamnet inte riktigt finns med.
        header("Location: ../index.php?login=wronguidpwd");
        exit();
      }
    }
  }
// Vi stänger sedan prep stat och mysql anslutningen.
  mysqli_stmt_close($stmt);
  mysqli_close($conn);
}
else {
	// Om användaren inte har klickat på signup-submit, så blir han bara tillbaka skickade till signup sidan.
	header("Location: ../signup.php");
  exit();
}
