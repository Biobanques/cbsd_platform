<?php

/**
 * classe embarquée Question, définit les objets Question dans les questionnaires
 * @author matthieu
 *
 */
class Question extends LoggableActiveRecord {

    public $id;

    /**
     *
     * @var type
     */
    public $label;

    /**
     *
     * @var type
     */
    public $label_fr;

    /**
     * type of the question. Values authorized :
     * input, date , radio, checkbox, text, image
     * @var type 
     */
    public $type;
    /*
     * css style applied to the label.
     */
    public $style;

    /**
     * values if question type is radio
     */
    public $values;

    /**
     * values if question type is radio and french setted
     */
    public $values_fr;
    public $defaultValue;

    /**
     * help text to add meta information around the question, displayed as an help button
     * @var type
     */
    public $help;

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
     * Returns the static model of the specified AR class.
     * @return Question the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated collection name
     */
    public function getCollectionName() {
        return 'Question';
    }

    /**
     * init a question with params setted into a questionForm
     * @param type $questionForm
     */
    public function setAttributesByQuestionForm($questionForm) {
        $this->id = $questionForm->id;
        $this->label = $questionForm->label;
        $this->label_fr = $questionForm->label;
        $this->style = $questionForm->style;
        $this->values = $questionForm->values;
        $this->defaultValue = $questionForm->defaultValue;
        $this->type = $questionForm->type;
        $this->precomment = $questionForm->precomment;
        $this->precomment_fr = $questionForm->precomment;
        if ($questionForm->help !== null) {
            $this->help = $questionForm->help;
        } else {
            $this->help = null;
        }
    }

    /**
     *
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array(
                'id, label, type',
                'required'
            ),
            array(
                'label,label_fr,type,values,defaultValue,style,precomment,precomment_fr,help', 'safe'
        ));
    }

    /**
     *
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'Id',
            'label' => 'question',
            'label_fr' => 'question',
            'help' => 'Info-bulle',
            'defaultValue' => 'Valeur par défaut'
        );
    }

    /**
     * get an array of types of questions possibles
     */
    public function getArrayTypes() {
        $res = array();
        $res ['input'] = Yii::t('common', 'simpleText');
        $res ['number'] = Yii::t('common', 'number');
        $res ['date'] = Yii::t('common', 'date');
        $res ['radio'] = Yii::t('common', 'radio');
        $res ['list'] = Yii::t('common', 'list');
        $res ['checkbox'] = Yii::t('common', 'checkbox');
        $res ['text'] = Yii::t('common', 'textarea');
        $res ['expression'] = Yii::t('common', 'expression');
        return $res;
    }

}
