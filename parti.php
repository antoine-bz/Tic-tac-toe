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

if ($id_partie=valider("partie"))
$id_partie=valider("partie");
else {
    $id_partie=valider("id_partie");
}
$partie=getPartieById($id_partie)[0];
$CROIX=$partie["id_utilisateur1"]; //CROIX
$ROND=$partie["id_utilisateur2"]; //ROND
$trait=$partie["trait"];
$grille=json_decode($partie["grille"]);
?>
  <style type="text/css">
        #winnerName{
            position: fixed;
            top: auto;
            left: auto;
            right: auto;
            whidth: auto;
            height: auto;
            background-color: white;
            box-shadow: 10px 1px 10px black;
            border: 1px solid black;
            display: none;
        }
        body {
            zoom: 120%;
            margin: 3%;
            background-color: grey;
            padding: 0px;
            font-family: Merriweather;
            color:black;
        }

        h1{
            color:black;
            text-align: center;
            text-decoration: underline;
        }

        .tab h2{
            text-decoration: underline;
            margin-top: 10px;
            margin-bottom: 5px;
            font-size: 25px;
        }
        .tab{
            color:black;
            text-align: center;
            background-color: white;
            box-shadow: 10px 1px 10px black;
            border: 1px solid black;
            padding: 5px;
            margin: 16px;
            width: auto;
            position: relative;
            float: left;
        }

        #grille{
            width : 300px;
            height : 300px;
            border-collapse: collapse;
            margin-left: auto;
            margin-right: auto;
        }
        .case {
            background-color: white;   
            border : solid 0.1px black;       
            width : 32%;
            height : 100%;
            float: left;
        }
        .ligne{
            height : 33%;
            width : 100%;
        }
        .case img{
            width : 100%;
            height : 100%;
        }
        .casier{
            width : 100%;
            height : 100%;
        }
        #tabTraitJouer{
            position: fixed;
            top: 0;
            left: 0;
        }

        
    </style>


<script type="text/javascript" >
    function JOUERCOUP(value){
        console.log(value);
        var form = document.getElementById(value);
        form.submit();
    }
    var grille = <?php echo json_encode($grille); ?>;
    //on transforme tout les element qui
    var joueur = {
        <?php echo "'nom$CROIX': '".getNameUser($CROIX)."'"; ?>,
        <?php echo "'nom$ROND': '".getNameUser($ROND)."'"; ?>
    };
    //on verifie si un joueur a gagné en verifiant toutes les combinaisons possibles (horizontalement, verticalement, diagonalement)
    //on verifie si la partie est finie en verifiant si la grille est pleine
    function checkFinish(){
        //vérif diag de la grille
        if (grille[0][0] == grille[1][1] && grille[1][1] == grille[2][2] && grille[0][0] != 0){
            return grille[0][0];
        }
        if (grille[0][2] == grille[1][1] && grille[1][1] == grille[2][0] && grille[0][2] != 0){
            return grille[0][2];
        }
        //vérif horizontal de la grille
        for (var i = 0; i < 3; i++){
            if (grille[i][0] == grille[i][1] && grille[i][1] == grille[i][2] && grille[i][0] != 0){
                return grille[i][0];
            }
        }
        //vérif vertical de la grille
        for (var i = 0; i < 3; i++){
            if (grille[0][i] == grille[1][i] && grille[1][i] == grille[2][i] && grille[0][i] != 0){
                return grille[0][i];
            }
        }
        return 0;
    }

    
    function initialiser(){
        var winner = document.getElementById("winnerName");
        console.log(winner);
    
        var win=checkFinish();
        console.log(win);
        winner.innerHTML="";
        if (win != 0 && win != -1){
            winner.innerHTML="La partie est terminée, "+joueur["nom"+win]+" a gagné.</br>Appuyer sur le bouton pour recommencer.";
            winner.style.display="block";
        }

    }
</script>
</head>
<!-- **** F I N **** H E A D **** -->


<!-- **** B O D Y **** -->
<body onload="initialiser();">

<span id="tabTraitJouer">
    <?php if (valider("id_user")){if ($CROIX==$id_user){  echo "Vous jouez les croix";  } else { echo "Vous jouez les ronds"; }} ?>
</span>

    <!--on affiche le vainqueur-->
    <div id="winnerName" ></div>
    <h1>Morpion</h1>
    <div id="grille" >
    <?php
        $i=0;
        for($i=0;$i<3;$i++){
            echo "<div class='ligne'>";
            for($j=0;$j<3;$j++){
                if($grille[$i][$j]==0){
                    if($trait==$id_user){
                        //echo "<div class='case'></div>";
                        echo "<a class='case' href=\"controleur.php?action=jouerPartie&id_partie=$id_partie&ligne=$i&colonne=$j\"></a>";
                        //echo "</div>";
                    }
                    else
                    echo "<div class='case'></div>";
                }
                else if($grille[$i][$j]==$CROIX){
                    echo "<div class='case'><img src='./img/croix.png' alt='croix'/></div>";
                }
                else if($grille[$i][$j]==$ROND){
                    echo "<div class='case'><img src='./img/rond.png' alt='rond'/></div>";
                }
            }
            echo "</div>";
        }
    ?>
    </div>

    
    <div id="tabTrait" class="tab">
        <h2>Trait:</h2>
        <div id="trait">
        <?php echo getNameUser($trait); echo ' (';
            if ($CROIX==$trait){
                echo "croix";
            } else  echo "rond";
            echo ')'; ?>
        </div>
    </div>
    

    
        
        <?php 
        if (valider("id_user"))
        {
            echo "<div id='tabRestart' class='tab'><h2>Recommencer:</h2>";
            if ($partie["recommencer"]!=0)
            $vote = 1;
            else
            $vote = 0;
            echo "<div id='restart'>".$vote." votes</div>"; 
            mkform();
            mkinput("hidden","action","VoteRestart");
            mkinput("hidden","id_partie",$id_partie);
            if ($partie["recommencer"]==0 || $partie["recommencer"]!=$id_user){
                mkinput("submit","","Recommencer");
            }
            endform();
            echo "</div>";
        }
        
        ?>   
</div>

    </body>