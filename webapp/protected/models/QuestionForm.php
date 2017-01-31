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
            array('id,label, type, idQuestionGroup', 'required'),
            array('values', 'valuesValidator'),
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
            'label' => Yii::t('common', 'label'),
            'idQuestionBefore' => Yii::t('common', 'questionPosition'),
            'idQuestionGroup' => Yii::t('common', 'questionGroup'),
            'style' => Yii::t('common', 'questionFloat'),
            'values' => Yii::t('common', 'values'),
            'precomment' => Yii::t('common', 'questionTitle'),
            'precomment_fr' => Yii::t('common', 'questionTitle'),
            'help' => Yii::t('common', 'help'),
        );
    }

    /**
     * get an array of types of questions possibles
     */
    public function getArrayTypes() {
        $res = array();
        $res ['input'] = "texte simple";
        $res ['number'] = "numérique";
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
        $res [''] = "Alignement à gauche";
        $res ['float:right'] = "Alignement à droite";
        return $res;
    }
   
    public function valuesValidator()
    {
        if ((isset($this->type) && $this->type == "radio")) {
            $this->validatorList->add(CValidator::createValidator('required', $this, 'values', array()));
            if ($this->values == "") {
                $this->addError('values', Yii::t('common', 'insertValues'));
            }
        }
    }

    public function getArrayGroups() {
        return $this->questionnaire->getArrayGroups();
    }
    
    public function getArrayQuestions() {
        return $this->questionnaire->getQuestions();
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
   
    public function validatewithId($form, $attributes = null, $clearErrors = true) {
        parent::validate($attributes, $clearErrors);
        foreach ($form->questions_group as $group) {
            if ($group->questions != "") {
                foreach ($group->questions as $question) {
                    if ($question->id == $this->id) {
                        $this->addError('id', Yii::t('common', 'loginExist'));
                    }
                }
            }
        }
        return !$this->hasErrors();
    }
}