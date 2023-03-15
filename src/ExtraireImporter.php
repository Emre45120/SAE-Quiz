<?php

require("connect.php");

$sql = "SELECT * FROM QUESTIONNAIRE";
$stmt = $connexion->prepare($sql);
$stmt->execute();
$questionnaires = $stmt->fetchAll();

		// Vérifier si le formulaire a été soumis
		if(isset($_POST['importer'])) {
			// Vérifier si un fichier a été choisi
			if(isset($_FILES['json_file']) && $_FILES['json_file']['error'] === UPLOAD_ERR_OK) {
				// Lire le contenu du fichier JSON
				$json_data = file_get_contents($_FILES['json_file']['tmp_name']);
				$data = json_decode($json_data, true);


				// Insertion des données dans les tables correspondantes
                foreach ($data as $table_name => $rows) {
                    foreach ($rows as $row) {
                        // Insertion dans la table UTILISATEUR
                        if ($table_name == 'UTILISATEUR') {
                            $nom = $row['nom'];
                            $new_email = $row['email'];
                            $mot_de_passe = $row['mot_de_passe'];
                            $est_admin = filter_var($row['est_admin'], FILTER_VALIDATE_BOOLEAN);
                            if ($est_admin == true) {
                                $est_admin = 1;
                            } else {
                                $est_admin = 0;
                            }
                            
                            if (empty($est_admin)) {
                                $est_admin = 0;
                            }
                            

                            
                            // Vérification si l'utilisateur existe déjà
                            $sql = "SELECT COUNT(*) FROM UTILISATEUR WHERE email = ?";
                            $stmt = $connexion->prepare($sql);
                            $stmt->execute([$new_email]);
                            $count = $stmt->fetchColumn();
                        
                            if ($count == 0) {
                                // Insertion de l'utilisateur
                                $sql = "INSERT INTO UTILISATEUR (nom, email, mot_de_passe, est_admin) VALUES (?, ?, ?, ?)";
                                $stmt = $connexion->prepare($sql);
                                $stmt->execute([$nom, $new_email, $mot_de_passe, $est_admin]);
                            }
                        }

                        // Insertion dans la table QUESTIONNAIRE
                        if ($table_name == 'QUESTIONNAIRE') {
                            $nomQ = $row['nomQ'];

                            // Vérification si le questionnaire existe déjà
                            $sql = "SELECT COUNT(*) FROM QUESTIONNAIRE WHERE nomQ = ?";
                            $stmt = $connexion->prepare($sql);
                            $stmt->execute([$nomQ]);
                            $count = $stmt->fetchColumn();
                        
                            if ($count == 0) {
                                // Insertion du questionnaire
                                $sql = "INSERT INTO QUESTIONNAIRE (nomQ) VALUES (?)";
                                $stmt = $connexion->prepare($sql);
                                $stmt->execute([$nomQ]);
                            }
                        }

                        // Insertion dans la table QUESTION
                        if ($table_name == 'QUESTION') {
                            $question = $row['question'];
                            $type = $row['type'];
                            $id_questionnaire = $row['id_questionnaire'];

                            // Vérification si la question existe déjà
                            $sql = "SELECT COUNT(*) FROM QUESTION WHERE question = ?";
                            $stmt = $connexion->prepare($sql);
                            $stmt->execute([$question]);
                            $count = $stmt->fetchColumn();
                        
                            if ($count == 0) {
                                // Insertion de la question
                                $sql = "INSERT INTO QUESTION (question, type, id_questionnaire) VALUES (?, ?, ?)";
                                $stmt = $connexion->prepare($sql);
                                $stmt->execute([$question, $type, $id_questionnaire]);
                            }
                        }

                        // Insertion dans la table REPONSE
                        if ($table_name == 'REPONSE') {
                            $reponse = $row['reponse'];
                            $est_correcte = filter_var($row['est_correcte'], FILTER_VALIDATE_BOOLEAN);
                            $id_question = $row['id_question'];
                            if ($est_correcte == true) {
                                $est_correcte = 1;
                            } else {
                                $est_correcte = 0;
                            }
                            
                            if (empty($est_correcte)) {
                                $est_correcte = 0;
                            }
                            
                            

                            // Vérification si la réponse existe déjà
                            $sql = "SELECT COUNT(*) FROM REPONSE WHERE reponse = ?";
                            $stmt = $connexion->prepare($sql);
                            $stmt->execute([$reponse]);
                            $count = $stmt->fetchColumn();
                        
                            if ($count == 0) {
                                // Insertion de la réponse
                                $sql = "INSERT INTO REPONSE (reponse, est_correcte, id_question) VALUES (?, ?, ?)";
                                $stmt = $connexion->prepare($sql);
                                $stmt->execute([$reponse, $est_correcte, $id_question]);
                            }
                        }

                        // Insertion dans la table SCORE
                        if ($table_name == 'SCORE') {
                            $id_utilisateur = $row['id_utilisateur'];
                            $id_questionnaire = $row['id_questionnaire'];
                            $score = $row['score'];

                            // Vérification si le score existe déjà
                            $sql = "SELECT COUNT(*) FROM SCORE WHERE id_utilisateur = ? AND id_questionnaire = ?";
                            $stmt = $connexion->prepare($sql);
                            $stmt->execute([$id_utilisateur, $id_questionnaire]);
                            $count = $stmt->fetchColumn();
                        
                            if ($count == 0) {
                                // Insertion du score
                                $sql = "INSERT INTO SCORE (id_utilisateur, id_questionnaire, score) VALUES (?, ?, ?)";
                                $stmt = $connexion->prepare($sql);
                                $stmt->execute([$id_utilisateur, $id_questionnaire, $score]);
                            }
                        }
                    }
                }
            }
            else {
                $erreur = "Le fichier n'est pas au format JSON";
                echo $erreur;
            }
        }

        if (isset($_POST['exporter'])) {
            $tables = array('UTILISATEUR', 'QUESTIONNAIRE', 'QUESTION', 'REPONSE', 'SCORE');
            $data = array();
        
            foreach ($tables as $table) {
                $query = $connexion->query("SELECT * FROM $table");
                $rows = array();
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $rows[] = $row;
                }
                $data[$table] = $rows;
            }
        
            $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        
            // Envoie le fichier en tant que réponse HTTP
            header('Content-Type: application/json; charset=utf-8');
            header('Content-Disposition: attachment; filename="data.json"');
            // Mettre en tampon la sortie pour envoyer les entêtes avant le JSON
            ob_end_clean(); // Envoie le contenu JSON et supprime le tampon
            echo $json;
            
        }
?>