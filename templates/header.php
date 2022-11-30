<?php

// Si la page est appelée directement par son adresse, on redirige en passant par la page index
if (basename($_SERVER["PHP_SELF"]) != "index.php")
{
  header("Location:../index.php");
  die("");
}

// Pose qq soucis avec certains serveurs...
echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<!-- **** H E A D **** -->
<head>  
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width" />
  <title>Morpion</title>
	<!-- Liaisons aux fichiers CSS et JS de Bootstrap -->
	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" />
	<link href="css/sticky-footer.css" rel="stylesheet" />
	<!--[if lt IE 9]>
	  <script src="js/html5shiv.js"></script>
	  <script src="js/respond.min.js"></script>
	<![endif]-->
	<script src="js/jquery.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<!-- **** F I N **** H E A D **** -->


<!-- **** B O D Y **** -->
<body>

<!-- style inspiré de http://www.bootstrapzero.com/bootstrap-template/sticky-footer --> 

<!-- Wrap all page content here -->
<div id="wrap">
  
  <!-- Fixed navbar -->
  <div class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="icon-bar"></span>
        </button>
	<a class="navbar-brand" href="index.php?view=accueil">Morpion</a>
      </div>
      <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
          <?php
            if (!valider("connecte", "SESSION")) {
              echo mkHeadLink("Connexion", "login", $view);
            }
            if (valider("connecte", "SESSION")) {
              echo mkHeadLinkControleur("Déconnexion", "logout", $view);
              echo mkHeadLink("Mes parties", "parties", $view);
            }
          ?>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </div>
  

<?php

// Affichage d'un message reçu par la page
if ($msg = valider("msg", "GET")) {
  echo "<div id=\"divMsg\">\n";
    echo "<p>$msg</p>\n";
  echo "</div>\n";
}

?>
<!-- Début du corps de page -->
<div class="container">

