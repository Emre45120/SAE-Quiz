<?php
// Connexion à la base de données
require("connect.php");

// Vérifier si l'utilisateur est connecté
session_start(); // démarrer la session
if (isset($_SESSION["email"])) {
    // l'utilisateur est connecté
    $is_authenticated = true;
    $email = $_SESSION["email"];
    
}else if ($_SESSION["admin"] == false) {
    // Rediriger l'utilisateur vers la page de connexion
    header("Location: Accueil.php");
    exit();
}

// Récupérer le nom de l'utilisateur connecté
$email = $_SESSION["email"];
$sql = "SELECT nom FROM UTILISATEUR WHERE email=:email";
$stmt = $connexion->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->execute();
$result = $stmt->fetch();
$nom_utilisateur = $result["nom"];

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $titre = $_POST["titre"];
    $questions = array();

    // Insérer les données dans la table QUESTIONNAIRE
    $sql = "INSERT INTO QUESTIONNAIRE (nomQ) VALUES (:titre)";
    $stmt = $connexion->prepare($sql);
    $stmt->bindParam(':titre', $titre);
    $stmt->execute();

    // Récupérer l'ID du questionnaire inséré
    $id_questionnaire = $connexion->lastInsertId();

    foreach ($_POST as $name => $value) {
        if (strpos($name, "question_") === 0) {
            $index = substr($name, strlen("question_"));
            $type = $_POST["type_question_" . $index];

            $question = array(
                "question" => $value,
                "type" => $type,
                "reponses" => null

            );
            // Récupérer les réponses pour la question
            if ($type === "choix_unique" || $type === "choix_multiple") {
                $reponses = array();
                $numrep =1;
                foreach ($_POST as $name => $value) {
                    echo "<h1> $name </h1>";
                    $est_correcte = 0;
                    $txt = "";
    
                    
                    if (strpos($name, "option_" . $index . "_". $numrep) === 0) {
                        // Récupérer le texte de la réponse
                        if ($value !== "") {
                            $txt = $value;
                        }
                        foreach ($_POST as $name => $value) {
                            if (strpos($name, "bonne_reponse_" . $index . "_". $numrep) === 0) {
                                // Vérifier si checkbox est cochée ou le radio est sélectionné
                                echo "<h2> ".$value." </h2>";
                                if ($value == "".$numrep) {
                                    $est_correcte = 1;
                                }
                            }
                        }

                        $numrep++;


                    }

                            

                    

                    // Vérifier si la réponse contient du texte avant de l'ajouter
                    if ($txt !== "") {
                        $reponse = array(
                            "txt" => $txt,
                            "est_correcte" => $est_correcte
                        );
                        $reponses[] = $reponse;
                    }
                }
                $question["reponses"] = $reponses;
            }

            if ($type === "libre" || $type === "slider") {
                foreach ($_POST as $name => $value) {
                    if (strpos($name, "option_" . $index . "_") === 0) {
                        if ($value !== "") {
                            $question["reponses"] = $value;
                        }
                    }
                }
               
            }


            $questions[] = $question;
            
        }
    }




    // Insérer les données dans la table QUESTION et REPONSE
    foreach ($questions as $question) {
        // Insérer la question dans la table QUESTION
        $sql = "INSERT INTO QUESTION (question, type, id_questionnaire) VALUES (:question, :type, :id_questionnaire)";
        $stmt = $connexion->prepare($sql);
        $stmt->bindParam(':question', $question["question"]);
        $stmt->bindParam(':type', $question["type"]);
        $stmt->bindParam(':id_questionnaire', $id_questionnaire);
        $stmt->execute();

        // Récupérer l'ID de la question insérée
        $id_question = $connexion->lastInsertId();

        // Insérer les réponses dans la table REPONSE
        if ($question["type"] === "choix_unique" || $question["type"] === "choix_multiple") {
            echo count($question["reponses"]);
            echo $question["reponses"][0]["txt"];
 
            foreach ($question["reponses"] as $reponse) {


                $sql = "INSERT INTO REPONSE (reponse,est_correcte, id_question) VALUES (:reponse,:estcorrecte,:id_question)";
                $stmt = $connexion->prepare($sql);
                $stmt->bindParam(':reponse', $reponse["txt"]);
                $stmt->bindParam(':estcorrecte', $reponse["est_correcte"]);
                $stmt->bindParam(':id_question', $id_question);
                $stmt->execute();
            }
        }else if ($question["type"] === "libre" || $question["type"] === "slider") {

            $sql = "INSERT INTO REPONSE (reponse,est_correcte, id_question) VALUES (:reponse,true,:id_question)";
            $stmt = $connexion->prepare($sql);
            $stmt->bindParam(':reponse', $question["reponses"]);
            $stmt->bindParam(':id_question', $id_question);
            $stmt->execute();
        }

    }

    // Rediriger l'utilisateur vers la page d'accueil
    header("Location: Accueil.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quiz</title>
    <link rel="stylesheet" type="text/css" href="css/styleAccueil.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/reset.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/header-6.css" />
</head>
<body>
<header class="site-header">
      <div class="site-header__top">
        <div class="wrapper site-header__wrapper top">
          <nav class="nav">
            <button class="nav__toggle" aria-expanded="false" type="button">
              menu
            </button>
            <ul class="nav__wrapper">
              <li class="nav__item"><a href="Accueil.php">Accueil</a></li>
              <li class="nav__item"><a href="questionnaire.php">Questionnaire</a></li>
              <li class="nav__item"><a href="score.php">Score</a></li>
              <?php if (isset($_SESSION["admin"]) && $_SESSION["admin"] == 1) { ?>
                    <li class="nav__item"><a href="admin.php">Admin</a></li>
                    <li class="nav__item"><a href="Createquestionaire.php">Créer un questionnaire</a></li>
                    <li class="nav__item"><a href="JSON.php">JSON</a></li>
                <?php } ?>
                
            </ul>
          </nav>
            <?php if (!$is_authenticated) { ?>
                <button class="button" onclick="window.location.href='conBD.php'">Se connecter</button>
            <?php } ?>
            <?php if ($is_authenticated) { ?>
                <button class="button" onclick="window.location.href='deconnecter.php'">Se déconnecter</button>
            <?php } ?>
        </div>
      </div>
      <div class="site-header__bottom">
        <div class="wrapper site-header__wrapper bottom">
          <a href="#" class="brand">SAE QUIZ</a>
        </div>
      </div>
    </header>

<div class="container">
    <!-- Formulaire de création d'un questionnaire -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h2>Créer un questionnaire</h2>

        <label for="titre">Titre du questionnaire :</label>
        <input type="text" id="titre" name="titre"><br><br>



    <!-- Ajout des questions -->
    <div id="questions">
        <h3>Questions :</h3>

        <div class="question">
            <label for="question_1">Question 1 :</label>
            <input type="text" id="question_1" name="question_1"><br>

            <label for="type_question_1">Type de question :</label>
            <select id="type_question_1" name="type_question_1" onchange="afficherOptions(this)">
                <option value="">-- Sélectionner un type de question --</option>
                <option value="choix_unique">Choix unique</option>
                <option value="choix_multiple">Choix multiple</option>
                <option value="libre">Réponse libre</option>
                <option value="slider">Slider</option>
            </select><br><br>

            <div id="options_question_1">
            </div>

            
        </div>

    </div>

    <!-- Bouton pour ajouter une question -->
    <button type="button" onclick="ajouterQuestion()">Ajouter une question</button><br><br>

    <input type="submit" name="creerquiz" value="Créer le quiz">
    </form>
<div>




<script>
    // Compteur pour les questions
    var compteur = 1;

    // Fonction pour ajouter une question
    function ajouterQuestion() {
        compteur++;

        // Créer un élément div pour la question
        var div = document.createElement("div");
        div.className = "question";

        // Créer un élément label pour la question
        var label = document.createElement("label");
        label.setAttribute("for", "question_" + compteur);
        label.innerHTML = "Question " + compteur + " :";

        // Créer un élément input pour la question
        var input = document.createElement("input");
        input.setAttribute("type", "text");
        input.setAttribute("id", "question_" + compteur);
        input.setAttribute("name", "question_" + compteur);

        // Ajouter le label et l'input à la div
        div.appendChild(label);
        div.appendChild(input);
        div.appendChild(document.createElement("br"));

        // Créer un élément label pour le type de question
        label = document.createElement("label");
        label.setAttribute("for", "type_question_" + compteur);
        label.innerHTML = "Type de question :";

        // Créer un élément select pour le type de question
        var select = document.createElement("select");
        select.setAttribute("id", "type_question_" + compteur);
        select.setAttribute("name", "type_question_" + compteur);
        select.setAttribute("onchange", "afficherOptions(this)");

        // Créer un élément option pour le type de question
        var option = document.createElement("option");
        option.setAttribute("value", "");
        option.innerHTML = "-- Sélectionner un type de question --";

        // Ajouter l'option au select
        select.appendChild(option);

        // Créer un élément option pour le type de question
        option = document.createElement("option");
        option.setAttribute("value", "choix_unique");
        option.innerHTML = "Choix unique";

        // Ajouter l'option au select
        select.appendChild(option);

        // Créer un élément option pour le type de question
        option = document.createElement("option");
        option.setAttribute("value", "choix_multiple");
        option.innerHTML = "Choix multiple";

        // Ajouter l'option au select
        select.appendChild(option);

        // Crée un élément option pour le type de question

        option = document.createElement("option");
        option.setAttribute("value", "libre");
        option.innerHTML = "Réponse libre";

        // Ajouter l'option au select
        select.appendChild(option);

        // Créer un élément option pour le type de question
        option = document.createElement("option");
        option.setAttribute("value", "slider");
        option.innerHTML = "Slider";

        // Ajouter l'option au select
        select.appendChild(option);

        // Ajouter le label et le select à la div
        div.appendChild(label);
        div.appendChild(select);
        div.appendChild(document.createElement("br"));
        div.appendChild(document.createElement("br"));

        // Créer un élément div pour les options
        var divOptions = document.createElement("div");
        divOptions.setAttribute("id", "options_question_" + compteur);

        // Ajouter la div des options à la div
        div.appendChild(divOptions);

        // Ajouter la div à la div des questions
        document.getElementById("questions").appendChild(div);

        // Ajouter un bouton pour supprimer la question
        var button = document.createElement("button");
        button.setAttribute("type", "button");
        button.setAttribute("onclick", "supprimerQuestion(2)");
        button.innerHTML = "Supprimer la question";

        // Ajouter le bouton à la div
        div.appendChild(button);

        
    }

    // Fonction pour afficher les options

    function afficherOptions(select) {
        // Récupérer l'id du select
        var id = select.getAttribute("id");

        // Récupérer le numéro de la question
        var numero = id.split("_")[2];

        // Récupérer la valeur du select
        var valeur = select.value;

        // Récupérer la div des options
        var divOptions = document.getElementById("options_question_" + numero);

        // Vider la div des options
        divOptions.innerHTML = "";

        // Si le type de question est "Choix unique" ou "Choix multiple"
        if (valeur == "choix_unique" || valeur == "choix_multiple") {

            var txt = document.createTextNode("Cochez la ou les bonne(s) réponse(s) :");
            divOptions.appendChild(txt);
            divOptions.appendChild(document.createElement("br"));



            for (var i = 1; i <= 4; i++) {
                // Créer un élément label pour l'option
                var label = document.createElement("label");
                label.setAttribute("for", "option_" + numero + "_" + i);
                label.innerHTML = "Option " + i + " :";

                // Créer un élément input pour l'option
                var input = document.createElement("input");
                input.setAttribute("type", "text");
                input.setAttribute("id", "option_" + numero + "_" + i);
                input.setAttribute("name", "option_" + numero + "_" + i);

                // Ajouter la checkbok bonne réponse pour l'option
                if (valeur == "choix_unique") {
                    var checkbox = document.createElement("input");
                    checkbox.setAttribute("type", "radio");
                    checkbox.setAttribute("id", "bonne_reponse_" + numero + "_" + i);
                    checkbox.setAttribute("name", "bonne_reponse_" + numero + "_" + i);
                    checkbox.setAttribute("value", i);
                    divOptions.appendChild(checkbox);
                } else {
                    var checkbox = document.createElement("input");
                    checkbox.setAttribute("type", "checkbox");
                    checkbox.setAttribute("id", "bonne_reponse_" + numero + "_" + i);
                    checkbox.setAttribute("name", "bonne_reponse_" + numero + "_" + i);
                    checkbox.setAttribute("value", i);
                    divOptions.appendChild(checkbox);
                }

                // Ajouter le label et l'input à la div des options
                divOptions.appendChild(label);
                divOptions.appendChild(input);
                divOptions.appendChild(document.createElement("br"));
            }

        }

        // Si le type de question est "Slider"
        if (valeur == "slider") {
            // Créer un élément label pour la bonne réponse
            var label = document.createElement("label");
            label.setAttribute("for", "option_" + numero + "_1");
            label.innerHTML = "Bonne réponse :";

            // Créer un élément input pour la bonne réponse
            var input = document.createElement("input");
            input.setAttribute("type", "number");
            input.setAttribute("id", "option_" + numero + "_1");
            input.setAttribute("name", "option_" + numero + "_1");

            // Ajouter le label et l'input à la div des options
            divOptions.appendChild(label);
            divOptions.appendChild(input);
            divOptions.appendChild(document.createElement("br"));

        }

        // Si le type de question est "libre"
        if (valeur == "libre") {
            // Créer un élément label pour la bonne réponse
            var label = document.createElement("label");
            label.setAttribute("for", "option_" + numero + "_1");
            label.innerHTML = "Bonne réponse :";

            // Créer un élément input pour la bonne réponse
            var input = document.createElement("input");
            input.setAttribute("type", "text");
            input.setAttribute("id", "option_" + numero + "_1");
            input.setAttribute("name", "option_" + numero + "_1");

            // Ajouter le label et l'input à la div des options
            divOptions.appendChild(label);
            divOptions.appendChild(input);
            divOptions.appendChild(document.createElement("br"));
        }




        
        
    }

    // Fonction qui permet de supprimer les options d'une question

    // Fonction qui permet de supprimer une question
    function supprimerQuestion(numeroQuestion) {
    // Récupérer l'élément de la question à supprimer
    var questionASupprimer = document.getElementsByClassName("question")[numeroQuestion - 1];

    // Supprimer l'élément de la question
    questionASupprimer.remove();

    // Mettre à jour le compteur de questions
    compteur--;    
    }

    

    </script>

    </body>
    </html>
    

    