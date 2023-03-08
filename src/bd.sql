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
    nomQ VARCHAR(255) NOT NULL
);

CREATE TABLE QUESTION (
    id_question INT AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(255) NOT NULL,
    type ENUM ('choix_multiple','choix_unique','libre','slider') NOT NULL,
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
    id_questionnaire INT,
    score INT,
    FOREIGN KEY (id_utilisateur) REFERENCES UTILISATEUR(id),
    FOREIGN KEY (id_questionnaire) REFERENCES QUESTIONNAIRE(id)
);


INSERT INTO QUESTIONNAIRE (id,nomQ) VALUES (1,'Quiz sur les mathématiques');
INSERT INTO QUESTIONNAIRE (id,nomq) VALUES (2,'Quiz sur la programmation');

INSERT INTO QUESTION (id_question, question,type,id_questionnaire) VALUES (1,"Quel est le résultat de 1+1 ? ","choix_unique",1);
INSERT INTO QUESTION (id_question, question,type,id_questionnaire) VALUES (2,"Quel est le résultat de 2+2 ?","choix_unique",1);
INSERT INTO QUESTION (id_question, question,type,id_questionnaire) VALUES (3,"Que fait 10+10 ?","choix_multiple",1);
INSERT INTO QUESTION (id_question, question,type,id_questionnaire) VALUES (5,"Quel est le théorème connue utilisé sur les triangle ?","libre",1);
INSERT INTO QUESTION (id_question, question,type,id_questionnaire) VALUES (6,"Mettre un pourcentage bonne rep à 50 ?","slider",1);
INSERT INTO QUESTION (id_question, question,type,id_questionnaire) VALUES (4,"Quel sont les différentes boucles qui existe ?","choix_multiple",2);


INSERT INTO REPONSE (id,reponse,est_correcte,id_question) VALUES (1,'2',True,1);
INSERT INTO REPONSE (id,reponse,est_correcte,id_question) VALUES (2,'5',False,1);

INSERT INTO REPONSE (id,reponse,est_correcte,id_question) VALUES (3,'4',True,2);
INSERT INTO REPONSE (id,reponse,est_correcte,id_question) VALUES (4,'3',False,2);

INSERT INTO REPONSE (id,reponse,est_correcte,id_question) VALUES (12,'10',False,3);
INSERT INTO REPONSE (id,reponse,est_correcte,id_question) VALUES (5,'20',True,3);
INSERT INTO REPONSE (id,reponse,est_correcte,id_question) VALUES (6,'5+15',True,3);

INSERT INTO REPONSE (id,reponse,est_correcte,id_question) VALUES (7,'for ',True,4);
INSERT INTO REPONSE (id,reponse,est_correcte,id_question) VALUES (8,'while',True,4);
INSERT INTO REPONSE (id,reponse,est_correcte,id_question) VALUES (9,'do while',True,4);
INSERT INTO REPONSE (id,reponse,est_correcte,id_question) VALUES (10,'do for',False,4);
INSERT INTO REPONSE (id,reponse,est_correcte,id_question) VALUES (11,'for while',False,4);

INSERT INTO REPONSE (id,reponse,est_correcte,id_question) VALUES (13,'pythagore',True,5);

INSERT INTO REPONSE (id,reponse,est_correcte,id_question) VALUES (14,'50',True,6);



INSERT INTO UTILISATEUR (id,nom,email,mot_de_passe,est_admin) VALUES (1,'admin','admin@gmail.com','admin',True);
INSERT INTO UTILISATEUR (id,nom,email,mot_de_passe,est_admin) VALUES (2,'emre','emre@gmail.com','emre',False);
INSERT INTO UTILISATEUR (id,nom,email,mot_de_passe,est_admin) VALUES (3,'mehdi','mehdi@gmail.com','mehdi',False);


