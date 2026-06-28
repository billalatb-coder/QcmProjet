DROP DATABASE IF EXISTS qcm_app;
CREATE DATABASE IF NOT EXISTS qcm_app;
USE qcm_app;

CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'user',
    statut VARCHAR(50) DEFAULT 'actif'
);

CREATE TABLE IF NOT EXISTS questions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    question TEXT NOT NULL,
    reponse1 VARCHAR(255) NOT NULL,
    reponse2 VARCHAR(255) NOT NULL,
    reponse3 VARCHAR(255) NOT NULL,
    reponse4 VARCHAR(255) NOT NULL,
    bonne_reponse INT NOT NULL,
    categorie VARCHAR(255) DEFAULT 'Général'
);

CREATE TABLE IF NOT EXISTS tentatives (
    id INT PRIMARY KEY AUTO_INCREMENT,
    utilisateur_id INT NOT NULL,
    score FLOAT NOT NULL,
    date DATETIME NOT NULL,
    duree INT DEFAULT 0
);

CREATE TABLE IF NOT EXISTS reponses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tentative_id INT NOT NULL,
    question_id INT NOT NULL,
    reponse_utilisateur INT NOT NULL,
    correcte BOOLEAN NOT NULL
);

-- Insertion des 100 questions
INSERT INTO questions (question, reponse1, reponse2, reponse3, reponse4, bonne_reponse, categorie) VALUES
-- Insolite & Mind-blowing (1-15)
('Quel est l''objet le plus sombre de l''univers connu ?', 'Un trou noir', 'Une nébuleuse', 'La matière noire', 'Un nuage de poussière', 1, 'Insolite'),
('Combien de temps met la lumière du Soleil pour atteindre la Terre ?', '8 secondes', '8 minutes', '8 heures', '8 jours', 2, 'Insolite'),
('Quel animal possède trois cœurs, neuf cerveaux et du sang bleu ?', 'Le poulpe', 'La méduse', 'Le calamar', 'Le requin', 1, 'Insolite'),
('Si vous étiez sur la Lune, de quelle couleur verriez-vous le ciel en plein jour ?', 'Bleu', 'Blanc', 'Noir', 'Gris', 3, 'Insolite'),
('Quelle invention a été initialement créée pour aider à arrêter de fumer ?', 'La cigarette filtre', 'La sucette', 'Le chewing-gum', 'Le patch', 3, 'Insolite'),
('Combien y a-t-il d''étoiles dans la Voie lactée (estimation) ?', '100 millions', '1 milliard', '100 à 400 milliards', '1 billion', 3, 'Insolite'),
('Quelle est la seule lettre qui n''apparaît pas dans le tableau périodique des éléments ?', 'J', 'Q', 'X', 'Z', 1, 'Insolite'),
('Quelle partie du corps humain ne peut pas se guérir d''elle-même ?', 'Le foie', 'Les dents', 'L''os', 'La cornée', 2, 'Insolite'),
('Lequel de ces fruits est en réalité une baie au sens botanique ?', 'La fraise', 'La framboise', 'La banane', 'La cerise', 3, 'Insolite'),
('Combien de temps a duré la guerre la plus courte de l''histoire ?', '38 minutes', '3 jours', '1 semaine', '1 mois', 1, 'Insolite'),
('Les empreintes digitales d''un animal ressemblent tellement à celles des humains qu''elles peuvent fausser des scènes de crime. Lequel ?', 'Le chimpanzé', 'Le koala', 'Le raton laveur', 'Le gorille', 2, 'Insolite'),
('Quelle est la vitesse d''un éternuement humain ?', '16 km/h', '50 km/h', '160 km/h', '300 km/h', 3, 'Insolite'),
('Quel pourcentage de l''univers est composé de matière visible ?', '5%', '25%', '50%', '75%', 1, 'Insolite'),
('Quel insecte peut survivre sans tête pendant plusieurs jours ?', 'La fourmi', 'La blatte (cafard)', 'L''araignée', 'Le moustique', 2, 'Insolite'),
('Combien de fois par seconde une abeille bat-elle des ailes ?', '50 fois', '100 fois', '200 fois', '500 fois', 3, 'Insolite'),

-- Cybersécurité & Tech (16-35)
('Quelle attaque informatique consiste à submerger un serveur de requêtes pour le faire planter ?', 'DDoS', 'Phishing', 'Man-in-the-Middle', 'Ransomware', 1, 'Cybersécurité'),
('Dans le domaine des réseaux, à quoi sert principalement un logiciel comme Cisco Packet Tracer ?', 'Créer des virus', 'Simuler des architectures réseaux', 'Héberger des sites web', 'Gérer des bases de données', 2, 'Tech'),
('Que signifie le "S" à la fin de HTTPS ?', 'Standard', 'System', 'Secure', 'Server', 3, 'Cybersécurité'),
('Quelle est la fonction principale d''un pare-feu (firewall) ?', 'Refroidir l''ordinateur', 'Filtrer le trafic réseau entrant et sortant', 'Accélérer la connexion internet', 'Nettoyer l''écran', 2, 'Cybersécurité'),
('En SQL, quelle commande est utilisée pour extraire des données d''une base de données ?', 'EXTRACT', 'GET', 'SELECT', 'PULL', 3, 'Tech'),
('Qu''est-ce que le Phishing ?', 'Une technique de pêche', 'Une méthode pour deviner les mots de passe', 'L''usurpation d''identité par email', 'Un virus destructeur', 3, 'Cybersécurité'),
('Quelle balise HTML est utilisée pour créer un lien hypertexte ?', '<link>', '<a>', '<href>', '<hyper>', 2, 'Tech'),
('Quel port par défaut est utilisé pour le protocole HTTP ?', '21', '22', '80', '443', 3, 'Tech'),
('Quel principe de sécurité vise à garantir qu''une donnée n''a pas été modifiée en transit ?', 'Confidentialité', 'Intégrité', 'Disponibilité', 'Traçabilité', 2, 'Cybersécurité'),
('Quel langage est principalement utilisé pour styliser une page web ?', 'HTML', 'PHP', 'CSS', 'JavaScript', 3, 'Tech'),
('Que fait la commande "ping" dans un terminal ?', 'Elle télécharge un fichier', 'Elle teste la connectivité avec une adresse IP', 'Elle ferme une application', 'Elle affiche l''heure', 2, 'Tech'),
('Qu''est-ce qu''une attaque "Man-in-the-Middle" ?', 'Une attaque physique sur un routeur', 'L''interception secrète des communications entre deux parties', 'Le vol d''un mot de passe par la force brute', 'L''infection par un cheval de Troie', 2, 'Cybersécurité'),
('Quel système de gestion de base de données est open source ?', 'Oracle', 'SQL Server', 'MariaDB', 'Microsoft Access', 3, 'Tech'),
('Quelle est la différence principale entre RAM et ROM ?', 'La RAM est permanente, la ROM est volatile', 'La RAM est volatile, la ROM est permanente', 'La RAM est moins chère', 'La ROM est plus rapide', 2, 'Tech'),
('Qu''est-ce que l''ingénierie sociale en cybersécurité ?', 'La programmation d''IA', 'La manipulation psychologique des personnes', 'La création de réseaux sociaux', 'Le cryptage des données', 2, 'Cybersécurité'),
('Que représente l''adresse IP de rebouclage (localhost) standard en IPv4 ?', '192.168.1.1', '10.0.0.1', '127.0.0.1', '255.255.255.0', 3, 'Tech'),
('Que signifie l''acronyme PHP aujourd''hui ?', 'Personal Home Page', 'PHP: Hypertext Preprocessor', 'Private Hosting Platform', 'Public HTML Parser', 2, 'Tech'),
('Quel algorithme de hachage est considéré comme obsolète et peu sécurisé aujourd''hui ?', 'SHA-256', 'Argon2', 'MD5', 'bcrypt', 3, 'Cybersécurité'),
('Lequel de ces éléments n''est pas une couche du modèle OSI ?', 'Application', 'Transport', 'Internet', 'Liaison de données', 3, 'Tech'),
('Quelle est la longueur en bits d''une adresse IPv6 ?', '32 bits', '64 bits', '128 bits', '256 bits', 3, 'Tech'),

-- Histoire (36-50)
('En quelle année a eu lieu la chute du mur de Berlin ?', '1987', '1989', '1991', '1993', 2, 'Histoire'),
('Qui était le premier empereur romain ?', 'Jules César', 'Néron', 'Auguste', 'Caligula', 3, 'Histoire'),
('Quel pharaon est célèbre pour sa tombe découverte intacte en 1922 ?', 'Ramsès II', 'Khéops', 'Akhenaton', 'Toutânkhamon', 4, 'Histoire'),
('Quelle reine de France a été guillotinée en 1793 ?', 'Catherine de Médicis', 'Marie-Antoinette', 'Aliénor d''Aquitaine', 'Anne de Bretagne', 2, 'Histoire'),
('Dans quelle ville a été signé le traité qui a mis fin à la Première Guerre mondiale ?', 'Londres', 'Berlin', 'Versailles', 'Vienne', 3, 'Histoire'),
('Qui a peint le plafond de la chapelle Sixtine ?', 'Léonard de Vinci', 'Michel-Ange', 'Raphaël', 'Donatello', 2, 'Histoire'),
('Quel explorateur a accompli le premier tour du monde (bien qu''il soit mort en route) ?', 'Christophe Colomb', 'Vasco de Gama', 'Fernand de Magellan', 'James Cook', 3, 'Histoire'),
('En quelle année l''homme a-t-il marché sur la Lune pour la première fois ?', '1965', '1969', '1971', '1973', 2, 'Histoire'),
('Quel empire était le plus grand par sa superficie totale continue ?', 'L''Empire Romain', 'L''Empire Mongol', 'L''Empire Britannique', 'L''Empire Ottoman', 2, 'Histoire'),
('Qui a été le premier président des États-Unis ?', 'Thomas Jefferson', 'Abraham Lincoln', 'George Washington', 'John Adams', 3, 'Histoire'),
('Quelle civilisation a construit le Machu Picchu ?', 'Les Aztèques', 'Les Mayas', 'Les Incas', 'Les Olmèques', 3, 'Histoire'),
('Quel événement a déclenché la Première Guerre mondiale ?', 'L''invasion de la Pologne', 'L''assassinat de l''archiduc François-Ferdinand', 'La chute du mur de Berlin', 'La Révolution russe', 2, 'Histoire'),
('Qui était surnommé le Roi-Soleil ?', 'Louis XIII', 'Louis XIV', 'Louis XV', 'Louis XVI', 2, 'Histoire'),
('Dans quel pays a eu lieu la révolution bolchevique de 1917 ?', 'France', 'Allemagne', 'Russie', 'Chine', 3, 'Histoire'),
('Quel chef d''État a instauré le Code civil en France en 1804 ?', 'Robespierre', 'Napoléon Bonaparte', 'Charles de Gaulle', 'Louis-Napoléon Bonaparte', 2, 'Histoire'),

-- Géographie & Transports (51-65)
('Quelle est la capitale de l''Australie ?', 'Sydney', 'Melbourne', 'Canberra', 'Brisbane', 3, 'Géographie'),
('Quel est le plus grand océan du monde ?', 'Océan Atlantique', 'Océan Indien', 'Océan Arctique', 'Océan Pacifique', 4, 'Géographie'),
('Dans la région parisienne, quelle ligne de RER relie l''est (Chelles-Gournay) à l''ouest ?', 'RER A', 'RER B', 'RER D', 'RER E', 4, 'Géographie'),
('Quel pays possède le plus grand nombre d''îles au monde ?', 'L''Indonésie', 'Les Philippines', 'La Suède', 'Le Canada', 3, 'Géographie'),
('Quel est le plus long fleuve du monde ?', 'L''Amazone', 'Le Nil', 'Le Yangtsé', 'Le Mississippi', 1, 'Géographie'),
('Combien de fuseaux horaires la Russie traverse-t-elle ?', '7', '9', '11', '13', 3, 'Géographie'),
('Dans quel pays se trouve le mont Kilimandjaro ?', 'Kenya', 'Tanzanie', 'Ouganda', 'Afrique du Sud', 2, 'Géographie'),
('Quel est le plus petit pays du monde ?', 'Monaco', 'Le Vatican', 'Nauru', 'Tuvalu', 2, 'Géographie'),
('Laquelle de ces villes n''est pas traversée par le Danube ?', 'Vienne', 'Budapest', 'Prague', 'Belgrade', 3, 'Géographie'),
('Quel pays appelle-t-on le Pays du Soleil Levant ?', 'La Chine', 'La Corée du Sud', 'Le Japon', 'Le Vietnam', 3, 'Géographie'),
('Quelle est la plus grande forêt tropicale du monde ?', 'La forêt du Congo', 'La forêt amazonienne', 'La forêt de Daintree', 'La forêt de Bornéo', 2, 'Géographie'),
('Combien de pays ont une frontière terrestre avec la France métropolitaine ?', '6', '7', '8', '9', 3, 'Géographie'),
('Quel est le désert le plus chaud du monde ?', 'Le Sahara', 'Le désert de Gobi', 'Le désert d''Atacama', 'La vallée de la Mort', 1, 'Géographie'),
('Sur quel continent se trouve la Patagonie ?', 'L''Afrique', 'L''Océanie', 'L''Amérique du Sud', 'L''Asie', 3, 'Géographie'),
('Quelle mer sépare l''Europe de l''Afrique ?', 'La mer Noire', 'La mer Rouge', 'La mer Méditerranée', 'La mer Caspienne', 3, 'Géographie'),

-- Sciences & Nature (66-80)
('Quel est l''élément chimique dont le symbole est O ?', 'Or', 'Osmium', 'Oxygène', 'Oganesson', 3, 'Sciences'),
('Combien d''os compte le corps d''un adulte humain ?', '196', '206', '216', '226', 2, 'Sciences'),
('Quelle est la planète la plus proche du Soleil ?', 'Vénus', 'Terre', 'Mercure', 'Mars', 3, 'Sciences'),
('Quel est le métal le plus abondant dans la croûte terrestre ?', 'Le fer', 'L''aluminium', 'Le cuivre', 'L''or', 2, 'Sciences'),
('Quelle est la force qui attire les objets vers le centre de la Terre ?', 'Le magnétisme', 'La friction', 'La gravité', 'L''inertie', 3, 'Sciences'),
('Qui a formulé la théorie de la relativité générale ?', 'Isaac Newton', 'Galilée', 'Albert Einstein', 'Nikola Tesla', 3, 'Sciences'),
('Quel est l''organe le plus lourd du corps humain ?', 'Le cœur', 'Le foie', 'Le cerveau', 'La peau', 4, 'Sciences'),
('À quelle température l''eau bout-elle au niveau de la mer ?', '50°C', '90°C', '100°C', '120°C', 3, 'Sciences'),
('Quelle vitamine est synthétisée par la peau lors de l''exposition au soleil ?', 'Vitamine A', 'Vitamine C', 'Vitamine D', 'Vitamine E', 3, 'Sciences'),
('Quel instrument mesure la pression atmosphérique ?', 'Le thermomètre', 'Le baromètre', 'L''anémomètre', 'L''hygromètre', 2, 'Sciences'),
('Comment appelle-t-on la transformation de l''eau liquide en vapeur ?', 'La condensation', 'La sublimation', 'L''évaporation', 'La fusion', 3, 'Sciences'),
('Quelle est l''unité de mesure de la résistance électrique ?', 'Le Volt', 'L''Ampère', 'L''Ohm', 'Le Watt', 3, 'Sciences'),
('Quel est le gaz le plus abondant dans l''atmosphère terrestre ?', 'L''oxygène', 'Le dioxyde de carbone', 'L''azote', 'L''hydrogène', 3, 'Sciences'),
('Lequel de ces animaux est un mammifère marin ?', 'Le pingouin', 'L''hippocampe', 'La baleine', 'La tortue', 3, 'Sciences'),
('Quelle est la molécule responsable de la couleur verte des plantes ?', 'Le carotène', 'La chlorophylle', 'La mélanine', 'L''hémoglobine', 2, 'Sciences'),

-- Culture & Art (81-90)
('Qui a écrit "Les Misérables" ?', 'Emile Zola', 'Victor Hugo', 'Gustave Flaubert', 'Molière', 2, 'Culture'),
('Dans quel musée peut-on admirer La Joconde ?', 'Le Prado', 'Le British Museum', 'Le Louvre', 'Le Musée d''Orsay', 3, 'Culture'),
('Quel réalisateur a dirigé le film "Inception" ?', 'Steven Spielberg', 'Christopher Nolan', 'Quentin Tarantino', 'Martin Scorsese', 2, 'Culture'),
('Qui a composé "Les Quatre Saisons" ?', 'Mozart', 'Beethoven', 'Bach', 'Vivaldi', 4, 'Culture'),
('Dans la mythologie grecque, qui est le dieu des océans ?', 'Zeus', 'Hadès', 'Poséidon', 'Apollon', 3, 'Culture'),
('Quel est le nom du sorcier à la cicatrice en forme d''éclair ?', 'Ron Weasley', 'Albus Dumbledore', 'Harry Potter', 'Draco Malfoy', 3, 'Culture'),
('Quel est le plus grand festival de cinéma en France ?', 'Le Festival de Deauville', 'Le Festival de Cannes', 'Les Césars', 'Le Festival d''Annecy', 2, 'Culture'),
('Quel sculpteur a réalisé "Le Penseur" ?', 'Rodin', 'Bourdelle', 'Camille Claudel', 'Giacometti', 1, 'Culture'),
('Quelle est la monnaie utilisée au Japon ?', 'Le Yuan', 'Le Won', 'Le Yen', 'Le Baht', 3, 'Culture'),
('Quel livre est le plus vendu au monde après la Bible ?', 'Le Seigneur des Anneaux', 'Don Quichotte', 'Le Petit Prince', 'Harry Potter', 2, 'Culture'),

-- Vie Quotidienne, Mécanique & Divers (91-100)
('En France, quelle est la cylindrée maximale d''un scooter que l''on peut conduire avec le simple Permis AM (ex-BSR) ?', '50 cc', '125 cc', '250 cc', '500 cc', 1, 'Mécanique'),
('Lequel de ces éléments n''est pas une pièce d''un moteur thermique ?', 'Le piston', 'La bougie', 'La courroie', 'Le routeur', 4, 'Mécanique'),
('Dans le domaine de la livraison urbaine, que représente une "zone chaude" (heat map) sur l''application d''un coursier ?', 'Une zone de danger', 'Une zone avec beaucoup de commandes', 'Une zone bloquée', 'Une zone de recharge', 2, 'Divers'),
('Quel format de fichier est généralement utilisé pour un CV professionnel numérique ?', 'DOCX', 'TXT', 'PDF', 'XLS', 3, 'Divers'),
('Quel acronyme est souvent utilisé pour désigner un Questionnaire à Choix Multiples ?', 'QCM', 'FAQ', 'SAV', 'RIB', 1, 'Divers'),
('Quel nom porte le système de billettique des transports en commun en Île-de-France ?', 'Oyster', 'Navigo', 'T-Ticket', 'MetroPass', 2, 'Divers'),
('Dans quel but utilise-t-on le portail web de l''ANTS en France ?', 'Commander des repas', 'Démarches administratives (cartes grises, permis)', 'Réserver des billets de train', 'Acheter des cryptomonnaies', 2, 'Divers'),
('Lorsqu''on parle d''un smartphone, que signifie le terme "OLED" ?', 'Un type d''écran', 'Un processeur', 'Un réseau mobile', 'Une batterie', 1, 'Tech'),
('En électronique, comment s''appelle le composant qui permet de stocker de l''énergie brièvement ?', 'La résistance', 'Le condensateur', 'La diode', 'Le transistor', 2, 'Tech'),
('Que signifie le sigle CV ?', 'Curriculum Vitae', 'Carrière et Vie', 'Chemin Valide', 'Course de Vitesse', 1, 'Divers');