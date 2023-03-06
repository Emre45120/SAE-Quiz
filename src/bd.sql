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
    id_question INT AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(255) NOT NULL,
    type ENUM ('choix_multiple','choix_unique','libre') NOT NULL,
    id_questionnaire INT,
    FOREIGN KEY (id_questionnaire) REFERENCES QUESTIONNAIRE(id)
);

CREATE TABLE REPONSE (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reponse VARCHAR(255) NOT NULL,
    est_correcte BOOLEAN NOT NULL,
    id_question INT,
    FOREIGN KEY (id_question) REFERENCES QUESTION(id_question)
);

CREATE TABLE SCORE (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT,
    score INT,
    FOREIGN KEY (id_utilisateur) REFERENCES UTILISATEUR(id)
);


INSERT INTO QUESTIONNAIRE (id,nom) VALUES (1,'Quiz sur les mathématiques');

INSERT INTO QUESTION (id_question, question,type,id_questionnaire) VALUES (1,"Quel est le résultat de 1+1","choix_unique",1);
INSERT INTO QUESTION (id_question, question,type,id_questionnaire) VALUES (2,"Quel est le résultat de 2+2","choix_unique",1);

INSERT INTO REPONSE (id,reponse,est_correcte,id_question) VALUES (1,'2',True,1);
INSERT INTO REPONSE (id,reponse,est_correcte,id_question) VALUES (2,'5',False,1);
INSERT INTO REPONSE (id,reponse,est_correcte,id_question) VALUES (3,'4',True,2);
INSERT INTO REPONSE (id,reponse,est_correcte,id_question) VALUES (4,'3',False,2);

INSERT INTO UTILISATEUR (id,nom,email,mot_de_passe,est_admin) VALUES (1,'admin','admin@gmail.com','admin',True);
INSERT INTO UTILISATEUR (id,nom,email,mot_de_passe,est_admin) VALUES (2,'emre','emre@gmail.com','emre',False);