<?php

/**
 * This is the MongoDB Document model class based on table "QuestionBloc".
 */
class QuestionBlocForm extends CFormModel
{
    public $title;
    public $questions;
    public $parent_group;
    public $id;
    public $title_fr;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title,id', 'required'),
            array('title', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('title,id,parent_group', 'safe'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'title' => Yii::t('common', 'titleBloc'),
            'questions' => 'Questions',
            'parent_group' => 'Groupe parent',
            'id' => Yii::t('common', 'idBlock')
        );
    }

    public function validatewithId($form, $attributes = null, $clearErrors = true) {
        parent::validate($attributes, $clearErrors);
        foreach ($form->questions_group as $group) {
            if ($group->id == $this->id)
                $this->addError('id', Yii::t('common', 'loginExist'));
        }
        return !$this->hasErrors();
    }

}