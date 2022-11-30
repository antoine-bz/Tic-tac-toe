<link rel="stylesheet" type="text/css" href="css/styleParties.css">
<?php 
//On affiche tout les parties de l'utilisateur connecté
if (!$_SESSION["connecte"]){
    header("Location:./index.php?view=login");
    die("");
}

  foreach (getParties(valider("id_user","SESSION")) as $partie)
  {
    if(valider("id_user","SESSION")==$partie["id_utilisateur1"])
    {
      $id_adversaire=$partie["id_utilisateur2"];
    }
    else
    {
      $id_adversaire=$partie["id_utilisateur1"];
    }
    $trait=getNameUser($partie["trait"]);
    $id=$partie['id_partie'];
    echo "<a href=\"controleur.php?view=partie&partie=$id\">" ;
    echo "<div class='partie'>";
    echo "<h3>Partie n°" . $partie['id_partie'] . "</h3>";
    echo "<p>Contre : " . getNameUser($id_adversaire) . "</p>";
    echo "<p>Trait : " . $trait . "</p>";
    echo "</div></a>";
  }

?>