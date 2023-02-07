DROP TABLE SCORE;
DROP TABLE utilisateur;
DROP TABLE REPONSE;
DROP TABLE QUESTION;
DROP TABLE Theme;

CREATE TABLE utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL
);

CREATE TABLE questionnaire (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL
);

CREATE TABLE question (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_question INT,
    id_questionnaire INT,
    FOREIGN KEY (id_question) REFERENCES question(id),
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
    score INT,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id)
);


INSERT INTO questionnaire (id,nom) VALUES (1,'Quiz sur les mathématiques');

INSERT INTO question (id,id_question, id_questionnaire) VALUES (1,1,1);
INSERT INTO question (id,id_question, id_questionnaire) VALUES (2,2,1);