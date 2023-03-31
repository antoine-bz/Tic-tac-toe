<?php

// Si la page est appelée directement par son adresse, on redirige en passant par la page index
if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
  header("Location:../index.php");
  die("");
}

include_once("libs/modele.php");

?>
<link rel="stylesheet" type="text/css" href="css/styleAccueil.css">
<h2>Bienvenue</h2>
<!--<p>Ceci est un projet personnel, il s'agit du jeu morpion qui a été reproduit en web avec un chat en temps réel.<br>
(Un compte est nécessaire pour jouer et utiliser le site.)</p>-->


