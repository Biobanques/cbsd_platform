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
     * comment on the top of the question
     * @var type
     */
    public $precomment;

    /**
     * comment on the top of the question
     * @var type
     */
    public $precomment_fr;

    /**
     * info-bulle
     * @var type
     */
    public $help;

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            // name, email, subject and body are required
            array('id,label, type', 'required'),
            array('label', 'length', 'max' => 500),
            array('id,idQuestionBefore,idQuestionGroup', 'length', 'max' => 50),
            array('type', 'length', 'max' => 10),
            array('style', 'length', 'max' => 100),
            array('values', 'length', 'max' => 500),
            array('precomment', 'length', 'max' => 500),
            array('precomment_fr', 'length', 'max' => 500),
            array('help', 'length', 'max' => 500)
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
            'idQuestionBefore' => 'La question devrait apparaître en dessous de',
            'idQuestionGroup' => 'Onglet de questions',
            'style' => 'Alignement de la question',
            'values' => 'Valeurs',
            'precomment' => 'Pour ajouter un titre au dessus de la question',
            'precomment_fr' => 'Pour ajouter un titre au dessus de la question',
            'help' => 'Info-bulle'
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
        $res ['expression'] = "expression";
        //$res ['image'] = "image";
        return $res;
    }

    /**
     * get an array of types of questions possibles
     */
    public function getArrayStyles() {
        $res = array();
        $res ['float:left'] = "Alignement à gauche";
        $res ['float:right'] = "Alignement à droite";
        return $res;
    }

    public function getArrayGroups() {
        return $this->questionnaire->getArrayGroups();
    }

    /**
     * copy attributes of questions to QuestionForm.
     * @param type
     */
    public function copy($currentQuestion, $computedGroup) {
        $this->label = $currentQuestion->label;
        $this->type = $currentQuestion->type;
        $this->style = $currentQuestion->style;
        $this->values = $currentQuestion->values;
        $this->precomment = $currentQuestion->precomment;
        $this->precomment_fr = $currentQuestion->precomment_fr;
        $this->help = $currentQuestion->help;
        $this->id = $currentQuestion->id;
        $this->idQuestionGroup = $computedGroup->id;
    }

}
