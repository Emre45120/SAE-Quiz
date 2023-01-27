CREATE TABLE utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL
);

CREATE TABLE questionnaire (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_categorie INT,
    id_theme INT,
    FOREIGN KEY (id_categorie) REFERENCES categorie(id),
    FOREIGN KEY (id_theme) REFERENCES theme(id)
);

CREATE TABLE QUESTION (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_questionnaire INT,
    FOREIGN KEY (id_questionnaire) REFERENCES questionnaire(id)
);

CREATE TABLE REPONSE (
    id INT AUTO_INCREMENT PRIMARY KEY,
    enonce VARCHAR(255) NOT NULL,
    est_correcte BOOLEAN NOT NULL,
    id_question INT,
    FOREIGN KEY (id_question) REFERENCES question(id)
);

CREATE TABLE SCORE (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT,
    id_questionnaire INT,
    score INT,
    date_passation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id),
    FOREIGN KEY (id_questionnaire) REFERENCES questionnaire(id)
);

CREATE TABLE CATEGORIE (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL
);

CREATE TABLE theme (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL
);

-- Explication de la bd :
-- Un questionnaire est lié à une catégorie et un thème
-- Par exemple : un questionnaire de catégorie "Mathématiques" et de thème "Calcul mental"

-- Un questionnaire est composé de questions
-- Une question est composée de réponses

-- Un utilisateur peut passer plusieurs questionnaires
-- Un questionnaire peut être passé par plusieurs utilisateurs

-- Un questionnaire est lié à une catégorie et un thème

