<?php

class Utilisateur {

    public $email;
    public $password;

    public function __construct($email, $password) {
        $this->email = $email;
        $this->password = $password;
    }

}

class Question {
    public $text;
    public $reponses = [];

    public function __construct($text) {
        $this->text = $text;
    }

    public function addReponse($text, $isCorrect) {
        $this->reponses[] = new Reponse($text, $isCorrect);
    }
}

class Reponse {
    public $text;
    public $isCorrect;

    public function __construct($text, $isCorrect) {
        $this->text = $text;
        $this->isCorrect = $isCorrect;
    }
}


class QuestionRadio extends Question {

    public function addReponse($text, $isCorrect) {
        if ($isCorrect) {
            parent::addReponse($text, $isCorrect);
        } else {
            throw new Exception("Une question de type radio ne peut avoir qu'une seule réponse correcte");
        }
    }
    
}

class QuestionCheckbox extends Question {
    
    public function addReponse($text, $isCorrect) {
        parent::addReponse($text, $isCorrect);
    }
        
}

class QuestionTexte extends Question {

    public function addReponse($text, $isCorrect) {
        if ($isCorrect) {
            throw new Exception("Une question de type texte ne peut pas avoir de réponse correcte");
        } else {
            parent::addReponse($text, $isCorrect);
        }
    }
    
}

class QuestionChoixMultiple extends Question {

    public function addReponse($text, $isCorrect) {
        parent::addReponse($text, $isCorrect);
    }

}

class QuestionClassement extends Question {

    public function addReponse($text, $isCorrect) {
        if ($isCorrect) {
            throw new Exception("Une question de type classement ne peut pas avoir de réponse correcte");
        } else {
            parent::addReponse($text, $isCorrect);
        }
    }
    
}




?>

