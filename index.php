<?php
/* Här är en av de coolaste funktionerna som finns i php, här använder vi require header.php.
Vi gör det eftersom alla sidor vi gör kommer att ha navbaren och alla tags etc,
vilket betyder att vi gör en php fil som håller navbaren och meta tags etc,
Sen sätter vi bara in koden med hjälp av require "header.php".
  */
require "header.php";

?>

<main>
    <div class="wrapper-main">
        <section class="section-default">
            <!--
    Här använder vi session för att ändra det content vi ser på sidan.
    Är du inloggad ser du en sak och är du utloggad ser du en sak.
    Det är Typ Document Object model editing i javascript.
    -->
	<?php
	if (!isset($_SESSION['id'])) {
		echo '<p class="login-status">You are logged out!</p>';
	}
	else if (isset($_SESSION['id'])) {
		echo '<p class="login-status">You are logged in!</p>';
	}
	?>


    </section>
    </div>
</main>

