<?php
// Vue de connexion - déjà réalisée

// Si la page est appelée directement par son adresse, on redirige en passant pas la page index
if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
  header("Location:../index.php?view=login");
  die("");
}

?>

<h2>Connexion</h2>

<div id="formLogin">
<form action="controleur.php" method="GET">
<input type="hidden" name="action" value="login" />
<label for="pseudo">Identifiant : </label> <input type="text" id="pseudo" name="pseudo" /><br />
<label for="passe">Mot de passe : </label> <input type="password" id="passe" name="passe" /><br />

<input type="submit" name="" value="Connexion" />
</form>

</div>
