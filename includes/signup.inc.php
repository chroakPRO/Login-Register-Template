<?php

/* Här har vi en av säkerhetsåtgärderna som jag skriver om.
Allting i dokumentet går under detta if statement, så om man inte har tryckt på knappen,
'signup-submit' Så kommer ingen kod att köras förutom det som står i else längst ner.
Vilket kommer att skicka tillbaka en till signup.php  */

if (isset($_POST['signup-submit'])) {


	/*
	 Här har vi ett exempel på det jag skriver i dbh.inc.php. Jag inkluderar require 'dbh.inc.php'
    Så vi kan lättare ha åtkomst till databasen genom $conn.
	 */

  require 'dbh.inc.php';

  // Här hämtar vi information från signup.php, så vi kan använda den.
  $username = $_POST['nameuser'];
  $password = $_POST['pwd'];
  $passwordRepeat = $_POST['pwd-repeat'];



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
  if (empty($username) || empty($password) || empty($passwordRepeat)) {
	  header("Location: ../signup.php?error=emptyfields&nameuser=".$username);
	  exit();
  }
  /*
	Med hjälp av preg_match så sätter vi lite regler när det kommer till användarnamnet.
    Du får inte ha några spaces, du får ha a-z i små bokstäver, A-Z i stora bokstäver och du får har siffrorna 0-9.
  	Stämmer inte dessa krav så kommer du få ett fel meddelande, både i adressbaren och i HTML form.
   */
  else if (!preg_match("/^[a-zA-Z0-9]*$/", $username)){
	  header("Location: ../signup.php?error=invalidusername");
	  exit();
  }

  /*
	Här har vi en lite mer complex preg_match(pattern) Som säger att du måste ha 1 siffra, 1 stor bokstav, 1 liten
  bokstav, 1 speciellt tecken, och det får inte vara kortare än 8 tecken och inte längre än 30 tecken
   */

  else if (!preg_match("/^((?=.*\d)(?=.*[A-Z])(?=.*\W).{8,30})$/", $password)) {
  	header("Location: ../signup.php?error=wrongpwd&nameuser=".$username);
  }

  // Vi tittar om lösenorden du har skrivit in matchar varandra.

  else if ($password !== $passwordRepeat) {
    header("Location: ../signup.php?error=passwordcheck&nameuser=".$username);
    exit();
  }
  else {

	/*
	 Nu måste vi felsöka det sista vilket söker om användarnamnet redan är taget eller inte. För du borde inte ha två
	 olika användare med samma användarnamn. Vi gör det genom SQL vilket betyder att vi måste använda oss utav
	prepared statments för att göra det säkert.
	 */

    /* Först måste vi skapa en SQL template, vilket betyder att vi lämnar ett ? efter nameuser.
	 */
    $sql = "SELECT NameUser FROM users WHERE NameUser=?;";
    // Vi initierar anslutningen.
    $stmt = mysqli_stmt_init($conn);
    // Som jag skrivit tidigare måste man alltid titta om något är fel innan man utför kod.
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      // Fungerar inte initationen eller att det inte går att ansluta till databasen så skickar vi felet error=sqlerror
      header("Location: ../signup.php?error=sqlerror");
      exit();
    }
    else {
  	/* Här bestämmer vi vad vi ska sätta in efter nameuser=? under ?, "s" står för string och $username står för en
  	var, som vi definerat längst upp i filen, hade vi tillexempel vilja titta efter email ocb användarnamn hade vi haft
   	"ss", $username, $email Vi kommer få exempel på detta lite senare.
  	*/
      mysqli_stmt_bind_param($stmt, "s", $username);
      // Vi kör sedan vårat statment, vi har ju gjort så att $stmt, innehåller databas annslutingen och SQL templaten.
      mysqli_stmt_execute($stmt);
      // Nu har vi mysqli store result, vilket förvarar datan som vi har hittat så vi kan använda den.
      mysqli_stmt_store_result($stmt);
      //Sen gör vi en var som visar rows för det vi har sökt efter.
      $resultCount = mysqli_stmt_num_rows($stmt);
      // Sen avslutar vi vårat pre statment
      mysqli_stmt_close($stmt);
     /* Nu när vi har information kan vi köra den genom ett If statement för att se om det stämmer. Har vi mer än 1
     row, så kommer vi få ett fel meddelande, är det noll rows så kommer koden att fortsätta.

     PS: allt detta är för att läsa data ifrån SQL databasen vi lägger inte in någon information eller något sånt vi
     tittar bara i den, och slänger ut ett svar.

     */
      if ($resultCount > 0) {
        header("Location: ../signup.php?error=usertaken");
        exit();
      }
      else {
      	// Samma sak här, vi gör ett template, dock är detta för att lägga in information inte för att få reda på
		  // information.
        $sql = "INSERT INTO users (NameUser, PasswordUser) VALUES (?, ?);";

        $stmt = mysqli_stmt_init($conn);
        // Fungerar inte initationen eller att det inte går att ansluta till databasen så skickar vi felet error=sqlerror
        if (!mysqli_stmt_prepare($stmt, $sql)) {
          header("Location: ../signup.php?error=sqlerror");
          exit();
        }
        else {

          /*
           Nu kommer det roliga, hashade lösenord, det finns olika sätt att kryptera text på, SHA256, SHA512, MD5, SHA1
          AES etc. Det kan vara svårt att veta vilken typ av algoritm som är säker och vilken som är osäker. Därför
          är det bra att använda sig utav password_has(PASSWORD_DEFAULT), vilket krypterar med hjälp av bcrypt.
          BCrypt är säkert eftersom det uppdateras hela tiden, vilket gör det väldigt svårt att knäcka. Det finns
          några du methoder du verkligen inte borde använda som tex. MD5, eller SHA1, båda två är realtivt enkla att
          knäcka idagens läge.
           */
          // Vi gör en var som vi kan lätt till kalla för att hasha våra lösenord. Det vi gör är att hasha alla
			// lösenord som vi får ifrån $password.
          $hashedPwd = password_hash($password, PASSWORD_DEFAULT);

          // Samma sak här som förut, vi binder i vårat prep statment!
			// Vi använder $hashedPwd istället för $password, så lösenord visas som hashade i databasen.
          mysqli_stmt_bind_param($stmt, "ss", $username, $hashedPwd);
         // Vi kör prep statment.
          mysqli_stmt_execute($stmt);
          // Om allting går igenom så kör vi sucess meddelande
          header("Location: ../signup.php?signup=success");
          exit();

        }
      }
    }
  }
  // Vi stänger vårat prep statement och våran anslutning till databasen.
  mysqli_stmt_close($stmt);
  mysqli_close($conn);
}
else {
  // Om användaren inte har klickat på signup-submit, så blir han bara tillbaka skickade till signup sidan.
  header("Location: ../signup.php");
  exit();
}
