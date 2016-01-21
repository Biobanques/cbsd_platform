<?php

/**
 * Formulaire pour enregistrer les valeurs saisies poru générer une question dans un formulaire
 */
class QuestionForm extends CFormModel
{
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
     * group of the question
     */
    public $idQuestionGroup;
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
            array('id,idQuestionBefore,idQuestionGroup', 'length', 'max' => 50),
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
            'label' => 'Etiquette',
            'idQuestionBefore' => 'Position de la question précédente',
            'idQuestionGroup' => 'Onglet de questions',
            'style' => 'Alignement de la question',
            'values' => 'Valeurs'
        );
    }

    /**
     * get an array of types of questions possibles
     */
    public function getArrayTypes() {
        $res = array();
        $res ['input'] = "texte simple";
        $res ['date'] = "date";
        $res ['radio'] = "radio bouton";
        $res ['list'] = "liste déroulante";
        $res ['checkbox'] = "case(s) à cocher";
        $res ['text'] = "aire de texte";
        //$res ['image'] = "image";
        return $res;
    }

    public function getArrayGroups() {
        return $this->questionnaire->getArrayGroups();
    }

}