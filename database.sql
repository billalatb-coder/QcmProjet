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

-- Insertion admin par defaut (mdp: admin123)
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES 
('Admin', 'Super', 'admin@qcm.local', '$2y$10$eE0o9Yk0.G3sR2Fv5C5w3uBw.N1t9.T4Fh7z4fP8vR6w1sX8/yv0a', 'admin');

-- Insertion de 15 questions de test
INSERT INTO questions (question, reponse1, reponse2, reponse3, reponse4, bonne_reponse, categorie) VALUES 
('Que signifie HTML ?', 'HyperText Markup Language', 'Hyper Transfer Markup Language', 'HighText Machine Language', 'Hyperlink and Text Markup Language', 1, 'Web'),
('Quel est le langage de style standard du web ?', 'JavaScript', 'HTML', 'CSS', 'PHP', 3, 'Web'),
('Comment déclarer une variable en JavaScript ?', 'v variableName;', 'var variableName;', 'variable variableName;', 'decl variableName;', 2, 'JavaScript'),
('Quel attribut HTML est utilisé pour définir le style en ligne ?', 'class', 'style', 'styles', 'font', 2, 'HTML'),
('Quelle balise sert à créer un lien hypertexte ?', '<a>', '<link>', '<href>', '<hyperlink>', 1, 'HTML'),
('Quelle fonction PHP sert à afficher du texte ?', 'print_text()', 'echo', 'display()', 'show()', 2, 'PHP'),
('Comment s\'appelle le système de gestion de base de données relationnelle le plus populaire ?', 'MongoDB', 'Redis', 'MySQL', 'Neo4j', 3, 'Base de données'),
('En CSS, comment sélectionner un élément avec l\'id "monId" ?', '.monId', '*monId', 'monId', '#monId', 4, 'CSS'),
('Lequel de ces éléments n\'est pas un langage de programmation ?', 'Python', 'Java', 'HTML', 'C++', 3, 'Général'),
('Quelle méthode HTTP est utilisée pour envoyer des données de manière sécurisée sans limite de taille ?', 'GET', 'POST', 'PUT', 'DELETE', 2, 'Web'),
('Qu\'est-ce que le DOM ?', 'Document Object Model', 'Data Object Model', 'Document Oriented Model', 'Data Oriented Model', 1, 'JavaScript'),
('Quel symbole est utilisé pour les commentaires sur une seule ligne en PHP ?', '<!-- -->', '/* */', '//', '<--', 3, 'PHP'),
('Laquelle de ces balises permet d\'insérer une image ?', '<image>', '<img>', '<src>', '<pic>', 2, 'HTML'),
('Quel mot-clé est utilisé pour créer une fonction en PHP ?', 'func', 'function', 'create', 'def', 2, 'PHP'),
('Quelle propriété CSS change la couleur du texte ?', 'text-color', 'font-color', 'color', 'background-color', 3, 'CSS');
