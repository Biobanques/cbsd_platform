<?php

/**
 * Formulaire pour enregistrer les valeurs saisies poru générer une question dans un formulaire
 */
class QuestionForm extends CFormModel {

    /**
     * working questionnaire.
     * set a the beginning
     * @var type 
     */
    public $questionnaire;

    /**
     * identifiant unique de la question
     * @var type 
     */
    public $id;

    /**
     * label de la question
     * @var type 
     */
    public $label;

    /**
     * type de la question : input, textarea, selectlist
     * @var type 
     */
    public $type;

    /**
     * position of the question id of the question before
     */
    public $idQuestionBefore;
    /*
     * style : after the question or go to the line
     * style can be a css line
     */
    public $style;

    /**
     * values if question type is radio or selec list
     * value sare separated by ;
     */
    public $values;

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            // name, email, subject and body are required
            array('id,label, type', 'required'),
            array('label', 'length', 'max' => 50),
            array('id,idQuestionBefore', 'length', 'max' => 50),
            array('type', 'length', 'max' => 10),
            array('style', 'length', 'max' => 100),
            array('values', 'length', 'max' => 500),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels() {
        return array(
            'idQuestionBefore' => 'Position de la question précédente'
        );
    }

    /**
     * get an array of types of questions possibles
     */
    public function getArrayTypes() {
        $res = array();
        $res ['input'] = "texte simple";
        $res ['radio'] = "radio bouton";
        $res ['list'] = "liste déroulante";
        $res ['checkbox'] = "case à cocher";
        $res ['text'] = "aire de texte";
        $res ['image'] = "image";
        return $res;
    }

    /**
     * get array questions for a questionnaire
     */
    public function getArrayQuestions() {
        $res = array();
        if ($this->questionnaire->questions_group != null) {
            foreach ($this->questionnaire->questions_group as $group) {
                if ($group->questions != null) {
                    foreach ($group->questions as $question) {
                        $res [$question->id] = $group->title . "::" . $question->id . "::" . $question->label;
                    }
                }
            }
        }
        return $res;
    }

}
