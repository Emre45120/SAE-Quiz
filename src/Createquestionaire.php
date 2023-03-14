<?php
// Connexion à la base de données
require("connect.php");

// Vérifier si l'utilisateur est connecté
session_start(); // démarrer la session
if (!isset($_SESSION["email"])) {
    // Rediriger l'utilisateur vers la page de connexion
    header("Location: conBD.php");
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
    echo"<h1>test</h1>";
    $titre = $_POST["titre"];
    $questions = array();

    // Insérer les données dans la table QUESTIONNAIRE
    $sql = "INSERT INTO QUESTIONNAIRE (nomQ) VALUES (:titre)";
    $stmt = $connexion->prepare($sql);
    $stmt->bindParam(':titre', $titre);
    $stmt->execute();

    // Récupérer l'ID du questionnaire inséré
    $id_questionnaire = $connexion->lastInsertId();

    echo "ID du questionnaire inséré : $id_questionnaire";
    echo "" .gettype($questions). "";

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
                $reponses =array();
                foreach ($_POST as $name => $value) {
                    echo "<h2>test</h2>";
                    if (strpos($name, "option_" . $index . "_") === 0) {
                        if ($value !== "") {
                            $reponses[] = $value;
                        }
                    }
                }
                
                $question["reponses"] = $reponses;
            }
            if ($type === "libre" || $type === "slider") {
                foreach ($_POST as $name => $value) {
                    echo "<h2>test</h2>";
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
            echo "<h1>test111</h1>";
            echo "" .gettype($question["reponses"]). "";
            echo "" .count($question["reponses"]). "";
            
            foreach ($question["reponses"] as $reponse) {
                echo "<h1>test111</h1>";
                $sql = "INSERT INTO REPONSE (reponse,est_correcte, id_question) VALUES (:reponse,true,:id_question)";
                $stmt = $connexion->prepare($sql);
                $stmt->bindParam(':reponse', $reponse);
                $stmt->bindParam(':id_question', $id_question);
                $stmt->execute();
            }
        }else if ($question["type"] === "libre" || $question["type"] === "slider") {
            echo "<h1>test222</h1>";
            $sql = "INSERT INTO REPONSE (reponse,est_correcte, id_question) VALUES (:reponse,true,:id_question)";
            $stmt = $connexion->prepare($sql);
            $stmt->bindParam(':reponse', $question["reponses"]);
            $stmt->bindParam(':id_question', $id_question);
            $stmt->execute();
        }

    }

    // Rediriger l'utilisateur vers la page d'accueil
    //header("Location: Accueil.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quiz</title>
    <link rel="stylesheet" type="text/css" href="css/styleAccueil.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="Accueil.php">Accueil</a></li>
                <li><a href="questionnaire.php">Questionnaire</a></li>
                <li><a href="score.php">Score</a></li>
                <?php if (!$is_authenticated) { ?>
                <li><a href="conBD.php">Se connecter</a></li>
                <?php } ?>
                <?php if ($is_authenticated) { ?>
                <li><a href="deconnecter.php">Se déconnecter</a></li>
                <?php } ?>
            </ul>
        </nav>
    </header>

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
            // Créer un élément label pour l'option 1
            var label = document.createElement("label");
            label.setAttribute("for", "option_" + numero + "_1");
            label.innerHTML = "Option 1 :";

            // Créer un élément input pour l'option 1
            var input = document.createElement("input");
            input.setAttribute("type", "text");
            input.setAttribute("id", "option_" + numero + "_1");
            input.setAttribute("name", "option_" + numero + "_1");

            // Ajouter le label et l'input à la div des options
            divOptions.appendChild(label);
            divOptions.appendChild(input);
            divOptions.appendChild(document.createElement("br"));

            // Créer un élément label pour l'option 2
            label = document.createElement("label");
            label.setAttribute("for", "option_" + numero + "_2");
            label.innerHTML = "Option 2 :";

            // Créer un élément input pour l'option 2
            input = document.createElement("input");
            input.setAttribute("type", "text");
            input.setAttribute("id", "option_" + numero + "_2");
            input.setAttribute("name", "option_" + numero + "_2");

            // Ajouter le label et l'input à la div des options
            divOptions.appendChild(label);
            divOptions.appendChild(input);
            divOptions.appendChild(document.createElement("br"));

            // Créer un élément label pour l'option 3
            label = document.createElement("label");
            label.setAttribute("for", "option_" + numero + "_3");
            label.innerHTML = "Option 3 :";

            // Créer un élément input pour l'option 3
            input = document.createElement("input");
            input.setAttribute("type", "text");
            input.setAttribute("id", "option_" + numero + "_3");
            input.setAttribute("name", "option_" + numero + "_3");
        
            // Ajouter le label et l'input à la div des options
            divOptions.appendChild(label);
            divOptions.appendChild(input);
            divOptions.appendChild(document.createElement("br"));

            // Créer un élément label pour l'option 4
            label = document.createElement("label");
            label.setAttribute("for", "option_" + numero + "_4");
            label.innerHTML = "Option 4 :";

            // Créer un élément input pour l'option 4
            input = document.createElement("input");
            input.setAttribute("type", "text");
            input.setAttribute("id", "option_" + numero + "_4");
            input.setAttribute("name", "option_" + numero + "_4");

            // Ajouter le label et l'input à la div des options
            divOptions.appendChild(label);
            divOptions.appendChild(input);
            divOptions.appendChild(document.createElement("br"));

            // Créer un élément label pour l'option 5
            label = document.createElement("label");
            label.setAttribute("for", "option_" + numero + "_5");
            label.innerHTML = "Option 5 :";

            // Créer un élément input pour l'option 5
            input = document.createElement("input");
            input.setAttribute("type", "text");
            input.setAttribute("id", "option_" + numero + "_5");
            input.setAttribute("name", "option_" + numero + "_5");

            // Ajouter le label et l'input à la div des options
            divOptions.appendChild(label);
            divOptions.appendChild(input);
            divOptions.appendChild(document.createElement("br"));
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
    

    