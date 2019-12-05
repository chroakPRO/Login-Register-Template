<?php
/* Här är en av de coolaste funktionerna som finns i php, här använder vi require header.php.
 Vi gör det eftersom alla sidor vi gör kommer att ha navbaren,
 och alla tags etc vilket betyder att vi gör en php fil som håller navbaren
och meta tags etc, och sen sätter vi bara in koden med hjälp av require "header.php". */

  require "header.php";
?>

    <main>
      <div class="wrapper-main">
        <section class="section-default">
       
            <h1>Signup</h1>
          <div class="h3div">
              <h3>Password must contain 1 Digit, 1 Uppercase Letter, 1 Special Symbol,
                  it must also be longer then 8 characters and shorter then 30 characters. </h3>
           </div>
          <?php
          /* Här har vi  felhantering i PHP form som visar sig i HTML. När försöker att sign up och
          någonting är fel, så skickas en data i GET, låt säga att du har skrivit ett
          felaktigt användarnamn vilket vi ser genom (signup.inc.php) du blir då
          vidarebefodrad till "Header("Location: ../signup.php?error=invalidnameuser");"
          vi kan då se att du har en data som säger Error=invalidnameuser,
          vi läser sedan all data som kommer in och med hjälp av if/else kod kan vi ändra HTML,
          så eftersom error är lika med invalidnameuser,
          så kommer en p klass att skapas som säger ogiltigt användarnamn! */
          if (isset($_GET["error"])) {
            if ($_GET["error"] == "emptyfields") {
              echo '<p class="signuperror">Alla fält måste vara i fyllda!</p>';
            }
            else if ($_GET["error"] == "invalidusername") {
              echo '<p class="signuperror">Ogiltigt Användarnamn!</p>';
          
            }
            else if ($_GET["error"] == "passwordcheck") {
              echo '<p class="signuperror">Dina Lösenord matchar inte!</p>';
            }
            else if ($_GET["error"] == "usertaken") {
              echo '<p class="signuperror">Ditt användarnamn är redan taget!</p>';
            }

          else if ($_GET["error"] == "wrongpwd") {
              echo '<p class="signuperror">Ditt lösenord möter inte kraven!</p>';
            }
          }
          // Samma sak här, fast här gör vi ett meddelande att registeringen lyckades.
          else if (isset($_GET["signup"])) {
            if ($_GET["signup"] == "success") {
              echo '<p class="signupsuccess">Registering Lyckades</p>';
            }
          }
          ?>
          <form class="form-signup" action="includes/signup.inc.php" method="post">
            <?php
           /*
           Här gör vi det möjligt för oss att skriva tillbaka användarnamnet så användaren,
           inte behöver skriva användarnamnet mer än en gång.
           Så det som händer är att användaren försöker sign-up, men det misslyckas.
           Då har vi koden "header("Location: ../signup.php?error=passwordcheck&uid=".$username);"
           Som säger att det misslyckades och vi skickar tillbaka en GET Req med $username.
           Som är en var som innehåller $_POST['nameuser']; vi skickar den dock genom GET.
           Vi fångar sedan upp GET'n och använder den för att återskriva användarnamnet åt dom.

           */
			if (!empty($_GET["nameuser"])) {
				echo '<input type="text" name="nameuser" placeholder="Username" value="'.$_GET["nameuser"].'">';
			}
			else {
				echo '<input type="text" name="nameuser" placeholder="Username">';
			}
            ?>
            <input type="password" name="pwd" placeholder="Password"> 
            <input type="password" name="pwd-repeat" placeholder="Repeat password">
            <button type="submit" name="signup-submit">Signup</button>
          </form>
        </section>
      </div>
    </main>


