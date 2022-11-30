<?php
// Pose qq soucis avec certains serveurs...
echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<!-- **** H E A D **** -->
<head>  
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
  <?php
  include_once "libs/maLibUtils.php";
  include_once "libs/maLibSQL.pdo.php";
  include_once "libs/maLibForms.php";
  include_once "libs/modele.php";
  include_once "libs/maLibBootstrap.php";
/*if (!valider("partie")){
    header("Location:./index.php");
    die("");
}*/
$id_user=valider("id_user");

$id_adversaire=valider("id_adversaire");

?>
  <style type="text/css">
    body {
        padding-top: 10px;
    }
    #champmessage{
      width: 80%;
      height: 30px;
      position : relative;
      float:bottom;
    }
    #Envoyer{
      width: 20%;
      height: 30px;
      position : relative;
      float:bottom;
    }
    #form{
      width: 100%;
      height: 30px;
      position : fixed;
      bottom: 0;
      left: 0;
    }
    #listMSG{
      bottom: 100px;
    }

    /*on souhaite afficher les messages de id_user en fond vert a droite et autres a gauche en fond gris  sous forme de bulle*/
    .message_user{
      background-color: lightgreen;
      float: right;
      width: 50%;
      height: auto;
      position : relative;
      margin-right: 10px;
      margin-left: 10px;
      border-radius: 10px;
      padding: 5px;
    }
    .message_adversaire{
      background-color: #EEEEEE;
      float: left;
      width: 50%;
      height: auto;
      position : relative;
      margin-right: 10px;
      margin-left: 10px;
      border-radius: 10px;
      padding: 7px;
    }
    .lbluser{
      float: right;
      position : relative;
    }
    .lbladver{
      float: left;
      position : relative
    }

    #blockhide{
      float: left;
      width: 50%;
      height: 30px;
      position : relative;
      margin-right: 10px;
      margin-left: 10px;
      border-radius: 10px;
      padding: 7px;
    }
  </style>

</head>
<!-- **** F I N **** H E A D **** -->
<script type="text/javascript">//scroll au chargement de la page
 //var i = setInterval('bla()', 5);
function bla()//scroll en bas de page
  {
  document.documentElement.scrollTop=document.documentElement.scrollHeight;
  }
</script>


<!-- **** B O D Y **** -->
<body onload="bla();">
<?php //affichage des messages
  $conv=getConversation($id_user,$id_adversaire);
  $id_conversation=$conv[0]["id_conversation"];
  $messages=getMessage($id_conversation);
  echo "<div id='listMSG'>";
  foreach ($messages as $message) {
    if ($message["id_utilisateur"]==$id_user) {
      
      echo "<p name=\"message_user\" class=\"message_user\">".$message["message"]."</p>";
      echo "<label for=\"message_user\" class='lbluser'>".getNameUser($message["id_utilisateur"])."</label>";
    }
    else{
      
      echo "<p name=\"message_adversaire\" class=\"message_adversaire\">".$message["message"]."</p>";
      echo "<label for=\"message_adversaire\" class='lbladver'>".getNameUser($message["id_utilisateur"])."</label>";
    }
  }
  echo "<p id=\"blockhide\">                 </p>";
  echo "</div>";
?>


<?php
echo "<span id='form'>";
mkform();
//mkinput("hidden","id_partie",$id_partie);
mkinput("hidden","id_user",$id_user);
mkinput("hidden","id_adversaire",$id_adversaire);
mkinput("hidden","action","nouveauMessage");
mkinput("hidden","from","chat");
echo "<input type=text name=\"message\" id=\"champmessage\"/>";
echo "<input type=submit id=\"Envoyer\"/>";
endform();
echo"</span>";
?>

</body>