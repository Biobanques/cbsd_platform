<?php

/**
 * This is the MongoDB Document model class based on table "QuestionBloc".
 */
class QuestionBloc extends LoggableActiveRecord
{
    public $title;
    public $questions;
      public $parent_group;
    //   public $id;
    public $title_fr;

    /**
     * Returns the static model of the specified AR class.
     * @return QuestionBloc the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated collection name
     */
    public function getCollectionName() {
        return 'QuestionBloc';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title', 'required'),
            array('title', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('title,_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'title' => 'Titre du bloc',
            'questions' => 'Questions',
            'parent_group' => 'Groupe parent'
        );
    }

    public function getBlocTitle() {
        $blocTitle = array();
        $bloc = QuestionBloc::model()->findAll();
        foreach ($bloc as $key => $values)
            $blocTitle[(string) $values->_id] = $values->title;
        $blocTitle[(string) $values->_id] = $values->title;
        return $blocTitle;
    }

}