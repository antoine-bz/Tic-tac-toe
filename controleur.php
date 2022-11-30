<?php
session_start();

	include_once "libs/maLibUtils.php";	
	include_once "libs/modele.php"; 
	include_once "libs/maLibSecurisation.php"; 
	// cf. injection de dépendances 


	$qs = "";

	if ($action = valider("action"))
	{
		ob_start ();
		echo "Action = '$action' <br />";
		// ATTENTION : le codage des caractères peut poser PB si on utilise des actions comportant des accents... 
		// A EVITER si on ne maitrise pas ce type de problématiques

		/* TODO: A REVOIR !!
		// Dans tous les cas, il faut etre logue... 
		// Sauf si on veut se connecter (action == Connexion)

		if ($action != "Connexion") 
			securiser("login");
		*/

		// Un paramètre action a été soumis, on fait le boulot...
		switch($action)
		{
			
			
			// Connexion //////////////////////////////////////////////////
			case 'Connexion' :
				// On verifie la presence des champs login et passe
				$qs = "?view=login&erreur=1&msg=" . urlencode("Login ou mot de passe manquant");
				if ($login = valider("login"))
				if ($passe = valider("passe"))
				{
					// On verifie l'utilisateur, 
					// et on crée des variables de session si tout est OK
					// Cf. maLibSecurisation
					if (verifUser($login,$passe)) {
						// tout s'est bien passé, doit-on se souvenir de la personne ? 
						$iduser=getIduser($password,$login);
						if (valider("remember")) {
							setcookie("idUser", $iduser, time()-3600);
							setcookie("login",$login , time()-3600);
							setcookie("passe",$password, time()-3600);
							setcookie("remember",true, time()-3600);
							setcookie("connecte",true, time()-3600);
						} else {
							setcookie("idUser", $iduser, time()-3600);
							setcookie("login","", time()-3600);
							setcookie("passe","", time()-3600);
							setcookie("remember",false, time()-3600);
						}
						$qs = "?view=accueil";
					}
					else $qs = "?view=login&erreur=1&msg=" . urlencode("Identifiants incorrects");
		
				}

				// On redirigera vers la page index automatiquement
			break;

			case 'Logout' :
			case 'logout' :
				// traitement métier
				session_destroy(); // 1) traitement 
				// 2) choisir la vue suivante 
				$qs = "?view=login";
			break;

			case "Inscription" :
				
				$qs = "?view=inscription&erreur=1&msg=" . urlencode("Identifiants manquants");
				if ($mail = valider("mail"))
				if ($password = valider("password")){
					$qs = "?view=inscription"; 
					switch (valider("main-categories")) {
						case '1':
							if ($nomEtudiant = valider("nomEtudiant"))
							if ($prenomEtudiant = valider("prenomEtudiant"))
							{
								$idEtudiants=creerEtudiant($prenomEtudiant,$nomEtudiant);
								$idEtud=intval($idEtudiants);
								creerConnexionEtudiant($idEtud,$password,$mail);
								$qs = "?view=inscription&msg=" . urlencode("Création réussie, connectez-vous"); 
							}
							else//Si les informations sont incorrectes
							$qs = "?view=inscription&erreur=1&msg=" . urlencode("Identifiants incorrects");
							break;
						case '2':
							if ($nomEntreprise = valider("nomEntreprise"))
							if ($secteurAct = valider("secteurAct"))	
							{
								$idEntreprises =creerEntreprise($nomEntreprise,$secteurAct);
								$idEnt=intval($idEntreprises);
								creerConnexionEntreprise($idEnt,$password,$mail);
								$qs = "?view=inscription&msg=" . urlencode("Création réussie, connectez-vous"); 
							}
							else //Si les informations sont incorrectes
							$qs = "?view=inscription&erreur=1&msg=" . urlencode("Identifiants incorrects");
							break;
						default:
							$qs = "?view=inscription&erreur=1&msg=" . urlencode("Identifiants incorrects case");
							break;
					}
				}

				// traitement métier
				// redirection vers la vue suivante
			break;
			case "Rechercher" :
				$secteur = valider("Secteur");
				$ville = valider("Ville" );
				$qs = "?view=recherche&type=r&secteur=" . urlencode($secteur). "&ville=" . urlencode($ville);
				break;
			case 'Filtrer':
				$secteur = valider("Secteur");
				$ville = valider("Ville" );
				$RemunerationOn =valider("Remuneration");
				if($RemunerationOn=="on"){
					$Remuneration=1;
				}else if($RemunerationOn=="off"){
					$Remuneration=0;
				}
				$Activité =valider("secteurAct");
				$DureeMin =valider("DuréeMin");
				$Publiee =valider("FiltrePubli");
				$TypeStage =valider("TypeStage");
				$DureeMax =valider("DuréeMax");
				$qs = "?view=recherche&type=f&secteur=" . urlencode($secteur). "&ville=" . urlencode($ville);
				$qs .= "&Remuneration=" . urlencode($Remuneration). "&Activité=" . urlencode($Activité);
				$qs .= "&DureeMin=" . urlencode($DureeMin). "&Publiee=" . urlencode($Publiee);
				$qs .= "&TypeStage=" . urlencode($TypeStage);
				$qs .= "&DureeMax=" . urlencode($DureeMax);
				break;

			case 'Enregistrer' :
			case 'Modifier':
				$idUser = valider("idUser","SESSION");
				//echo $idUser;
				if(isEntreprise($idUser)){
					
					if($modif = valider("modif")){
						$idEntreprises=getIdentreprises(valider("idUser","SESSION"));
						$nom=valider("nom");
						$adresse=valider("adresse");
						$telephone=valider("telephone");
						$mail=valider("mail");
						$password=valider("password");
						UpdateEntreprise($idEntreprises,$nom,$adresse,$telephone);
						UpdateConnexion($idUser,$mail,$password);
						$qs = "?view=compteEntreprise";
					}
					else $qs = "?view=compteEntreprise&modifier=1";
				}	
				else if(isEtudiant($idUser)){
				
					if($modif = valider("modif")){
						$idEtudiants=getIdetudiant(valider("idUser","SESSION"));
						$nom=valider("nom");
						$prenom=valider("prenom");
						$age=valider("age");
						$adresse=valider("adresse");
						$telephone=valider("telephone");
						$mail=valider("mail");
						$password=valider("password");
						UpdateEtudiant($idEtudiants,$nom,$prenom,$age,$adresse,$telephone);
						UpdateConnexions($idUser,$mail,$password);
						$qs = "?view=compteEtudiant";
					}
					else $qs = "?view=compteEtudiant&modifier=1";
				}
				break;	
			case'publierannonce':
				$qs = "?view=publication&erreur=1&msg=" . urlencode("Informations incorrects");
				$idEntreprises=getIdentreprises(valider("idUser","SESSION"));
				if($nom=valider("nom"))
				if($duree=valider("duree"))
				if($type=valider("type"))
					{
						$description=valider("description");
						$Remuneration=valider("remuneration");
						$date=valider("date");
						NvAnnonce($nom,$idEntreprises,$Remuneration,$date,$duree,$type,$description);
						$qs = "?view=publication&msg=" . urlencode("Publication réussie");
					}
				break;
			case 'supprimerannonce':
				$qs = "?view=myannonces&msg=" . urlencode("annonce supprimée");
				$idAnnonce = valider('idannonce');
				Deletefav2($idAnnonce);
				DeleteRep($idAnnonce);
				DeleteAnn($idAnnonce);
				break;

			case 'supprimerfavEtudiant':
				
				$idAnnonce = valider('idannonce');
				$idEtudiant = valider('idetudiant');
				Deletefav($idAnnonce,$idEtudiant);
				$qs = "?view=annonce&id=$idAnnonce&msg=" . urlencode("favoris supprimé");
				break;
			case 'ajouterfavEtudiant':
				$idAnnonce = valider('idannonce');
				$idEtudiant = valider('idetudiant');
				ajoutFav($idEtudiant,$idAnnonce);
				$qs = "?view=annonce&id=$idAnnonce&msg=" . urlencode("favoris ajouté");
				break;
			case 'SupprimerReponse':
				$idAnnonce = valider('idannonce');
				$idEtudiant = valider('idetudiant');
				DeleteRep2($idEtudiant,$idAnnonce);
				$qs = "?view=annonce&id=$idAnnonce&msg=" . urlencode("réponse supprimée");
				break;
		case 'PostulerAnnonce':
				
				$idAnnonce = valider('idannonce');
				$idEtudiant = valider('idetudiant');
				$message = valider('message');
				ajoutReponse($idEtudiant,$idAnnonce,$message);
				$qs = "?view=annonce&id=$idAnnonce&msg=" . urlencode("vous venez de postuler à cette annonce");
				break;
		case 'reponsePostuler':
				
				$idAnnonce = valider('idannonce');
				$idEtudiant = valider('idetudiant');
				$reponse = valider('reponse');
				if($reponse=="Accepter"){
					$rep='yes';
				}
				else if($reponse=="Refuser"){
					$rep="no";
				}
				majRepReponse($idEtudiant,$idAnnonce,$rep);
				$qs = "?view=annonce&id=$idAnnonce";
				break;
		}

	}

	// On redirige toujours vers la page index, mais on ne connait pas le répertoire de base
	// On l'extrait donc du chemin du script courant : $_SERVER["PHP_SELF"]
	// Par exemple, si $_SERVER["PHP_SELF"] vaut /chat/data.php, dirname($_SERVER["PHP_SELF"]) contient /chat

	$urlBase = dirname($_SERVER["PHP_SELF"]) . "/index.php";
	// On redirige vers la page index avec les bons arguments

	header("Location:" . $urlBase . $qs);

	// On écrit seulement après cette entête
	ob_end_flush();
	
?>










