<?php

/**
 * classe embarquée Question, définit les objets Question dans les questionnaires
 * @author matthieu
 *
 */
class Question extends LoggableActiveRecord
{
    public $id;
    public $label;
    public $label_fr;
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
        $this->type = $questionForm->type;
    }

    /**
     *
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array(
                'id',
                'required'
            ),
            array(
                'label,label_fr,type,value,style', 'safe'
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
        );
    }

}