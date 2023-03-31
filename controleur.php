<?php

  include_once "libs/maLibUtils.php";
  include_once "libs/maLibSQL.pdo.php";
  include_once "libs/maLibSecurisation.php";
  include_once "libs/modele.php"; 
  
  // Démarrage de la session
  session_start();
  
  // On réutilise la query string pour la renvoyer à la prochaine page
  // Le tableau $qs peut être complété pour être renvoyé à la page de destination
  $qs = $_GET;
  // On peut aussi définir une query_string sous forme de chaîne de caractères
  //$qs = "";

  if ($action = valider("action"))
  {
    ob_start ();
    //echo "Action = '$action' <br />";
    // ATTENTION : le codage des caractères peut poser PB si on utilise des actions comportant des accents... 
    // A EVITER si on ne maitrise pas ce type de problématiques
    
    // Toutes les actions du contrôleur sont réservées aux utilisateur connectés, sauf l'opération de connexion
    if ($action != "login") 
      securiser("index.php");
    
    // Un paramètre action a été soumis, on fait le boulot...
    switch($action)
    {
      // Connexion
      case 'login' :
        // Par sécurité, on ne recopie pas les éléments dans la query string (pseudo, passe)
        $qs = [];
        // On extrait et verifie la présence des champs pseudo et passe
        if ($pseudo = valider("pseudo"))
        if ($passe = valider("passe"))
        {
          // On vérifie l'utilisateur et on remplit les variables de session
          if (verifUser($pseudo,$passe)) {
            // Tout s'est bien passé : on redirige vers la page d'accueil
            $qs["view"] = "accueil";
          } else {
            // Si erreur de connexion : on redirige vers le formulaire de connexion
            $qs["view"] = "login";
            // Avec un message d'erreur :
            $qs["msg"] = "Identifiant ou mot de passe invalide";
          }
        }
      break;
      
      // Déconnexion
      case 'logout' :
        // On détruit la session
        session_destroy();
        // On redirige vers la page de connexion
        $qs["view"] = "login";
        // Avec un message :
        $qs["msg"] = "Déconnexion réussie";
      break;
      
      case 'parties' :
        $qs["view"] = "parties";
      break;

      case 'jouerPartie':
        $id_user=valider("id_user","SESSION");
        $id_partie=valider("id_partie");
        $ligne=valider("ligne");
        $colonne=valider("colonne");

        $partie=getPartieById($id_partie)[0];    
        $parties=json_decode($partie["grille"]);        
        $parties[$ligne][$colonne]=$id_user;
        $partie["grille"]= json_encode($parties);

        if(valider("id_user","SESSION")==$partie["id_utilisateur1"])
        {
          $id_adversaire=$partie["id_utilisateur2"];
        }
        else
        {
          $id_adversaire=$partie["id_utilisateur1"];
        }

        $partie["trait"]=$id_adversaire;

        //echo json_encode($partie);
        UpdateGrille($id_partie,$partie["grille"],$partie["trait"]);
        $qs=array();
        
        header("Location:./parti.php?partie=$id_partie&id_user=$id_user");
        die("");
        $qs["view"]="partie";
        $qs["partie"]=$id_partie;
        break;

        case "VoteRestart":
          $id_user=valider("id_user","SESSION");
          $id_partie=valider("id_partie");
          $partie=getPartieById($id_partie)[0];
          if(valider("id_user","SESSION")==$partie["id_utilisateur1"])
            {
              $id_adversaire=$partie["id_utilisateur2"];
            }
            else
            {
              $id_adversaire=$partie["id_utilisateur1"];
            } 
          if ($partie["recommencer"]==0)
          {
            VoteRestart($id_partie,$id_user);
          }
          else
          {
            $grille="[[0,0,0],[0,0,0],[0,0,0]]";
            if (rand(0,1)==0)
            {
              $trait=$partie["id_utilisateur1"];
            }
            else
            {
              $trait=$partie["id_utilisateur2"];
            }
            restart($id_partie,$trait,$grille);
            
          }
         
          forcerefresh($id_partie,$id_adversaire);
          header("Location:./parti.php?partie=$id_partie&id_user=$id_user");
          die("");
          $qs["view"]="partie";
          $qs["partie"]=$id_partie;
          break;

          case 'testNouveau':
            $id_user=valider("id_user","SESSION");
            $id_partie=valider("id_partie");
            if(testnouveau($id_partie)==$id_user)
            {
              unsetnouveau($id_partie);
              echo "refreshGrille";
              die("");
            }
            else
            {
              echo "0";
              die("");
            }
          break;

          case 'nouveauMessage':
            $id_user=valider("id_user","SESSION");
            $id_partie=valider("id_partie");
            $id_adversaire=valider("id_adversaire");
            $message=valider("message");
            $from=valider("from");

            if ($message=="")
            {
              header("Location:./chat.php?id_adversaire=$id_adversaire&id_user=$id_user");
              die("");
            }

            $conv=getConversation($id_user,$id_adversaire);
            echo $id_adversaire;
            $id_conversation=$conv[0]["id_conversation"];

            posterMessage($id_conversation,$id_user,$message);
            $qs=array();
            if ($from=="chat")
            {
              header("Location:./chat.php?id_adversaire=$id_adversaire&id_user=$id_user");
              die("");
            }
            $qs["view"]="partie";
            $qs["partie"]=$id_partie;
          break;
          
          case 'testStatut':
            if(!$id_user=valider("id_user","SESSION")){
              $id_user=valider("id_user");
            }
            $id_adversaire=valider("id_adversaire");
            $conv=getConversation($id_user,$id_adversaire);
            $id_conversation=$conv[0]["id_conversation"];
            if(testStatut($id_conversation)==$id_user)
            {
              unsetStatut($id_conversation);
              echo "refreshChat";
              die("");
            }
            else
            {
              echo "0";
              die("");
            }
          break;
            
    }
  }

  // On redirige toujours vers la page index, mais on ne connait pas le répertoire de base
  // On l'extrait donc du chemin du script courant : $_SERVER["PHP_SELF"]
  // Par exemple, si $_SERVER["PHP_SELF"] vaut /chat/data.php, dirname($_SERVER["PHP_SELF"]) contient /chat
  $urlBase = dirname($_SERVER["PHP_SELF"]) . "/index.php";

  // On redirige vers la page index avec les bons arguments
  rediriger($urlBase, $qs);

  // On écrit seulement après cette entête
  ob_end_flush();
  
?>
