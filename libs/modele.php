<?php

/*
Partie modèle : on effectue ici tous les traitements sur la base de données (lecture, insertion, suppression, mise à jour).

Quelques squelettes de fonctions vous sont donnés, à titre purement indicatif.
Il n'est pas nécessaire de les respecter, et cette liste n'est pas exhaustive.
*/

include_once("maLibSQL.pdo.php");

// Identification : vérifie qu'un utilisateur $login peut se connecter avec $passe
function verifUtilisateurBdd($login, $passe)
{
  return SQLGetChamp("
    SELECT id_utilisateur
    FROM Utilisateur
    WHERE pseudo='$login'
      AND passe='$passe';
  ");
}

//On récupère les parties de l'utilisateur connecté
function getParties($id_utilisateur)
{
  return parcoursRs(SQLSelect("
    SELECT *
    FROM Partie
    WHERE id_utilisateur1='$id_utilisateur' OR id_utilisateur2='$id_utilisateur';
  "));
}

function getNameUser($id_utilisateur)
{
  return SQLGetChamp("
    SELECT pseudo
    FROM Utilisateur
    WHERE id_utilisateur='$id_utilisateur';
  ");
}

function getPartieById($id_partie)
{
  return parcoursRs(SQLSelect("
    SELECT *
    FROM Partie
    WHERE id_partie='$id_partie';
  "));
}

function UpdateGrille($id_partie, $grille, $trait)
{
  SQLUpdate("
    UPDATE Partie
    SET grille='$grille', trait='$trait',nouveau='$trait'
    WHERE id_partie='$id_partie';
  ");
}

function VoteRestart($id_partie,$id_user)
{
  SQLUpdate("
    UPDATE Partie
    SET recommencer='$id_user'
    WHERE id_partie='$id_partie';
  ");
}

function  restart($id_partie,$trait,$grille)
{
  SQLUpdate("
    UPDATE Partie
    SET grille='$grille', trait='$trait', recommencer='0'
    WHERE id_partie='$id_partie';
  ");
}

function testnouveau($id_partie)
{
  return SQLGetChamp("
    SELECT nouveau
    FROM Partie
    WHERE id_partie='$id_partie';
  ");
}

function unsetnouveau($id_partie)
{
  SQLUpdate("
    UPDATE Partie
    SET nouveau='0'
    WHERE id_partie='$id_partie';
  ");
}

function forcerefresh($id_partie,$id_user)
{
  SQLUpdate("
    UPDATE Partie
    SET nouveau='$id_user'
    WHERE id_partie='$id_partie';
  ");
}

function getConversation($id_user1, $id_user2)
{
  return parcoursRs(SQLSelect("
    SELECT *
    FROM Conversation
    WHERE (id_utilisateur1='$id_user1' AND id_utilisateur2='$id_user2') OR (id_utilisateur1='$id_user2' AND id_utilisateur2='$id_user1');
  "));
}
function getConversationById($id_conversation)
{
  return parcoursRs(SQLSelect("
    SELECT *
    FROM Conversation
    WHERE id_conversation='$id_conversation';
  "));
}

function getMessageUser($id_conversation,$id_user)
{
  return parcoursRs(SQLSelect("
    SELECT *
    FROM Message
    WHERE id_conversation='$id_conversation' AND id_utilisateur='$id_user';
  "));
}

function getMessage($id_conversation)
{
  return parcoursRs(SQLSelect("
    SELECT *
    FROM Message
    WHERE id_conversation='$id_conversation'
    Order by date;
  "));
}


function posterMessage($id_conversation,$id_utilisateur,$message)
{
  SQLInsert("
    INSERT INTO Message (id_conversation, id_utilisateur, message,date)
    VALUES ('$id_conversation', '$id_utilisateur', '$message',NOW());
  ");
  $conv=getConversationById($id_conversation);
  if ($conv[0]['id_utilisateur1']==$id_utilisateur)
  {
    $id_adversaire=$conv[0]['id_utilisateur2'];
  }
  else {
    $id_adversaire=$conv[0]['id_utilisateur1'];
  }
  SQLUpdate("
    UPDATE Conversation
    SET statut=$id_adversaire
    WHERE id_conversation='$id_conversation'");
}

function testStatut($id_conversation)
{
  return SQLGetChamp("
    SELECT statut
    FROM Conversation
    WHERE id_conversation='$id_conversation';
  ");
}

function unsetStatut($id_conversation)
{
  SQLUpdate("
    UPDATE Conversation
    SET statut='0'
    WHERE id_conversation='$id_conversation';
  ");
}


?>
