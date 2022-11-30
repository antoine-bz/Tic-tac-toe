    CREATE TABLE  IF NOT EXISTS `Utilisateur` ( `id_utilisateur` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Identifiant interne unique de l''utilisateur', `pseudo` varchar(50) NOT NULL COMMENT 'Pseudo de l''utilisateur', `passe` varchar(50) NOT NULL COMMENT 'Mot de passe de l''utilisateur' ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

    INSERT INTO `Utilisateur` (`id_utilisateur`, `pseudo`, `passe`) VALUES (1, 'player1', 'pwd1'), (2, 'player2', 'pwd2');

    -- CREATION de la table `Partie` --
    -- on retrouve l'id de la partie et l'id des utilisateurs et la variable qui indique le statut de la partie

    CREATE TABLE  IF NOT EXISTS `Partie` ( 
        `id_partie` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Identifiant interne unique de la partie' , 
        `id_utilisateur1` int(11) NOT NULL COMMENT 'Identifiant interne unique de l''utilisateur 1',
        `id_utilisateur2` int(11) NOT NULL COMMENT 'Identifiant interne unique de l''utilisateur 2',
        `trait` int(1) NOT NULL COMMENT 'Trait de la partie',
        `grille` varchar(50) NOT NULL COMMENT 'Grille de la partie',
        `recommencer` int(1) NOT NULL COMMENT 'Recommencer la partie',
        `nouveau` int(1) NOT NULL COMMENT 'Mise à jour de la partie',
        `scoreJ1` int(1) NOT NULL COMMENT 'Score de l''utilisateur 1',
        `scoreJ2` int(1) NOT NULL COMMENT 'Score de l''utilisateur 2'
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

    ALTER TABLE `Partie` ADD FOREIGN KEY (`id_utilisateur1`) REFERENCES `Utilisateur` (`id_utilisateur`);
    ALTER TABLE `Partie` ADD FOREIGN KEY (`id_utilisateur2`) REFERENCES `Utilisateur` (`id_utilisateur`);

    INSERT INTO `Partie` VALUES (NULL, 1, 2,1 ,'[[0,0,0],[0,0,0],[0,0,0]]',0,0,0,0);
    -- Creation des relations entre les tables --
    -- l'id des utilisateurs est une clé primaire de id_utilisateur1 et id_utilisateur2 de la table Partie

    -- CREATION de la table `conversation` --
    -- on retrouve l'id de la conversation et l'id des utilisateurs et la variable qui indique le statut de la conversation --
    CREATE TABLE  IF NOT EXISTS `Conversation` ( 
        `id_conversation` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Identifiant interne unique de la conversation' , 
        `id_utilisateur1` int(11) NOT NULL COMMENT 'Identifiant interne unique de l''utilisateur 1',
        `id_utilisateur2` int(11) NOT NULL COMMENT 'Identifiant interne unique de l''utilisateur 2',
        `statut` int(1) NOT NULL COMMENT 'Statut de la conversation'
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

    ALTER TABLE `Conversation` ADD FOREIGN KEY (`id_utilisateur1`) REFERENCES `Utilisateur` (`id_utilisateur`);
    ALTER TABLE `Conversation` ADD FOREIGN KEY (`id_utilisateur2`) REFERENCES `Utilisateur` (`id_utilisateur`);

    -- CREATION de la table `message` --
    -- on retrouve l'id de la conversation et l'id des utilisateurs et la variable qui indique le statut de la conversation--
    CREATE TABLE  IF NOT EXISTS `Message` ( 
        `id_message` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Identifiant interne unique du message' , 
        `id_conversation` int(11) NOT NULL COMMENT 'Identifiant interne unique de la conversation',
        `id_utilisateur` int(11) NOT NULL COMMENT 'Identifiant interne unique de l''utilisateur',
        `message` varchar(50) NOT NULL COMMENT 'Message de la conversation',
        `date` datetime NOT NULL COMMENT 'Date du message'
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

    ALTER TABLE `Message` ADD FOREIGN KEY (`id_conversation`) REFERENCES `Conversation` (`id_conversation`);
    ALTER TABLE `Message` ADD FOREIGN KEY (`id_utilisateur`) REFERENCES `Utilisateur` (`id_utilisateur`);

    INSERT INTO `Conversation` VALUES (NULL, 1, 2,0);



