<?php

/*
Ce fichier définit diverses fonctions permettant de faciliter la production de mises en formes complexes : 
tableaux, formulaires, ...
*/

// Exemple d'appel :  mkLigneEntete($data,array('pseudo', 'couleur', 'connecte'));
function mkLigneEntete($tabAsso,$listeChamps=false)
{
  // Fonction appelée dans mkTable, produit une ligne d'entête
  // contenant les noms des champs à afficher dans mkTable
  // Les champs à afficher sont définis à partir de la liste listeChamps 
  // si elle est fournie ou du tableau tabAsso

  if (!$listeChamps)  // listeChamps est faux  : on utilise le not : '!'
  {
    // tabAsso est un tableau associatif dont on affiche TOUTES LES CLES
    echo "\t<tr>\n";
    foreach ($tabAsso as $cle => $val)  
    {
      echo "\t\t<th>$cle</th>\n";
    }
    echo "\t</tr>\n";
  }
  else    // Les noms des champs sont dans $listeChamps   
  {
    echo "\t<tr>\n";
    foreach ($listeChamps as $nomChamp)  
    {
      echo "\t\t<th>$nomChamp</th>\n";
    }
    echo "\t</tr>\n";
  }
}

function mkLigne($tabAsso,$listeChamps=false)
{
  // Fonction appelée dans mkTable, produit une ligne   
  // contenant les valeurs des champs à afficher dans mkTable
  // Les champs à afficher sont définis à partir de la liste listeChamps 
  // si elle est fournie ou du tableau tabAsso

  if (!$listeChamps)  // listeChamps est faux  : on utilise le not : '!'
  {
    // tabAsso est un tableau associatif
    echo "\t<tr>\n";
    foreach ($tabAsso as $cle => $val)  
    {
      echo "\t\t<td>$val</td>\n";
    }
    echo "\t</tr>\n";
  }
  else  // les champs à afficher sont dans $listeChamps
  {
    echo "\t<tr>\n";
    foreach ($listeChamps as $nomChamp)  
    {
      echo "\t\t<td>$tabAsso[$nomChamp]</td>\n";
    }
    echo "\t</tr>\n";
  }
}

// Exemple d'appel :  mkTable($users,array('pseudo', 'couleur', 'connecte'));  
function mkTable($tabData,$listeChamps=false)
{

  // Attention : le tableau peut etre vide 
  // On produit un code ROBUSTE, donc on teste la taille du tableau
  if (count($tabData) == 0) return;

  echo "<table border=\"1\">\n";
  // afficher une ligne d'entete avec le nom des champs
  mkLigneEntete($tabData[0],$listeChamps);

  //tabData est un tableau indicé par des entier
  foreach ($tabData as $data)  
  {
    // afficher une ligne de données avec les valeurs, à chaque itération
    mkLigne($data,$listeChamps);
  }
  echo "</table>\n";

  // Produit un tableau affichant les données passées en paramètre
  // Si listeChamps est vide, on affiche toutes les données de $tabData
  // S'il est défini, on affiche uniquement les champs listés dans ce tableau, 
  // dans l'ordre du tableau
}

// Produit un menu déroulant portant l'attribut name = $nomChampSelect

// Produit les options d'un menu déroulant à partir des données passées en premier paramètre
// $champValue est le nom des cases contenant la valeur à envoyer au serveur
// $champLabel est le nom des cases contenant les labels à afficher dans les options
// $selected contient l'identifiant de l'option à sélectionner par défaut
// si $champLabel2 est défini, il indique le nom d'une autre case du tableau 
// servant à produire les labels des options
// si transfoLabel2 est défini, il doit s'agir d'un tableau associatif qui permet de transformer la valeur du $champLabel2
function mkSelect($nomChampSelect, $tabData, $champValue, $champLabel, $suppl="", $selected=false,$champLabel2=false, $transfoLabel2=[])
{
  $multiple=""; 
  if (preg_match('/.*\[\]$/',$nomChampSelect)) $multiple =" multiple =\"multiple\" ";

  echo "<select $suppl $multiple name=\"$nomChampSelect\">\n";
  foreach ($tabData as $data)
  {
    $sel = "";  // par défaut, aucune option n'est préselectionnée 
    // MAIS SI le champ selected est fourni
    // on teste s'il est égal à l'identifiant de l'élément en cours d'affichage
    // cet identifiant est celui qui est affiché dans le champ value des options
    // i.e. $data[$champValue]
    if ( ($selected) && ($selected == $data[$champValue]) )
      $sel = "selected=\"selected\"";

    echo "<option $sel value=\"$data[$champValue]\">";
    echo  $data[$champLabel];
    if ($champLabel2)   // SI on demande d'afficher un second label
      if (isset($transfoLabel2[$data[$champLabel2]])) {
        echo $transfoLabel2[$data[$champLabel2]];
      } else {
        echo  " ($data[$champLabel2])";
      }
    echo "</option>\n";
  }
  echo "</select>\n";
}

// Produit une balise de formulaire NB : penser à la balise fermante !!
function mkForm($action="controleur.php", $method="get") {
  echo "<form action=\"$action\" method=\"$method\" >\n";
}
// Produit la balise fermante
function endForm() {
  echo "</form>\n";
}

// Produit un champ formulaire
function mkInput($type, $name, $value="", $misc=[]) {  
  // On produit l'id de la balise, si défini dans misc
  $id = "";
  if (isset($misc["id"])) {
    $id = " id=\"$misc[id]\"";
  }
  
  // On produit sa classe, si définie dans misc
  $classe = "";
  if (isset($misc["class"])) {
    $classe = " class=\"$misc[class]\"";
  }
    
  // On produit le fait que l'élément est coché par défaut, si défini dans misc
  $selectionne = "";
  if (isset($misc["checked"]) && $misc["checked"]) {
    $selectionne = " checked=\"checked\"";
  }
  
  // On peut produire d'autres attributs dans misc
  $other = "";
  if (isset($misc["other"])) {
    $other = $misc["other"];
  }
    
  // La balise de formulaire
  $res = "<input$id type=\"$type\" name=\"$name\" value=\"$value\"$selectionne$classe $other/>\n";
  
  // On produit la balise label, si dénifie dans misc
  $label = "";
  if (isset($misc["id"]) && isset($misc["label"])) {
    $label = "<label$classe $other for=\"$misc[id]\">$misc[label]</label>\n";
    // On place le label par rapport à la balise input
    if (isset($misc["positionLabel"]) && $misc["positionLabel"] === "après") {
      $res = $res . $label;
    } else {
      $res = $label . $res;
    }
  }
  
  echo $res;
}

// Produit un champ formulaire de type radio ou checkbox
// Et coche/sélectionne cet élément si le quatrième argument est vrai
function mkRadioCb($type, $name, $value, $checked=false, $misc=[]) {
  $misc["checked"] = $checked;
  mkInput($type, $name, $value, $misc);
}

?>
