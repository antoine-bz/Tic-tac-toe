<?php


/*
Ce fichier définit diverses fonctions permettant de faciliter la production de mises en formes complexes
Il est utilisé en conjonction avec le style de bootstrap et insère des classes bootstrap
*/

function mkHeadLink($label, $view, $currentView="", $class="")
{
	// fFabrique un lien pour l'entête en insèrant la classe 'active' si view = currentView

	// EX: <?=mkHeadLink("Accueil","accueil",$view)
	// produit <li class="active"><a href="index.php?view=accueil">Accueil</a></li> si $view= accueil

	if ($view == $currentView) 
		$class .= " active";
	return "<li class=\"$class\"> <a href=\"index.php?view=$view\">$label</a></li>";
}

function mkHeadLinkControleur($label, $action)
{
	// Idem, en faisant directement appel au contrôleur

	// EX: <?=mkHeadLink("Déconnexion","logout")
	// produit <li><a href="controleur.php?action=logout">Déconnexion</a></li>

	return "<li> <a href=\"controleur.php?action=$action\">$label</a></li>";
}

?>
