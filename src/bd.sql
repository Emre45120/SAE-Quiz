DROP TABLE SCORE;
DROP TABLE UTILISATEUR;
DROP TABLE REPONSE;
DROP TABLE QUESTION;
DROP TABLE QUESTIONNAIRE;

CREATE TABLE UTILISATEUR (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    est_admin BOOLEAN NOT NULL
);

CREATE TABLE QUESTIONNAIRE (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL
);

CREATE TABLE QUESTION (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_question INT,
    id_questionnaire INT,
    FOREIGN KEY (id_question) REFERENCES QUESTION(id),
    FOREIGN KEY (id_questionnaire) REFERENCES QUESTIONNAIRE(id)
);

CREATE TABLE REPONSE (
    id INT AUTO_INCREMENT PRIMARY KEY,
    enonce VARCHAR(255) NOT NULL,
    est_correcte BOOLEAN NOT NULL,
    id_question INT,
    FOREIGN KEY (id_question) REFERENCES QUESTION(id)
);

CREATE TABLE SCORE (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT,
    score INT,
    FOREIGN KEY (id_utilisateur) REFERENCES UTILISATEUR(id)
);


INSERT INTO QUESTIONNAIRE (id,nom) VALUES (1,'Quiz sur les mathématiques');

INSERT INTO QUESTION (id,id_question, id_questionnaire) VALUES (1,1,1);
INSERT INTO QUESTION (id,id_question, id_questionnaire) VALUES (2,2,1);

INSERT INTO UTILISATEUR (id,nom,email,mot_de_passe,est_admin) VALUES (1,'admin','admin@gmail.com','admin',True);
INSERT INTO UTILISATEUR (id,nom,email,mot_de_passe,est_admin) VALUES (2,'emre','emre@gmail.com','emre',False);