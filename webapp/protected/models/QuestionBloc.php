<?php

/**
 * This is the MongoDB Document model class based on table "QuestionBloc".
 */
class QuestionBloc extends EMongoDocument
{
	public $title;
        public $questions;

	/**
	 * Returns the static model of the specified AR class.
	 * @return QuestionBloc the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated collection name
	 */
	public function getCollectionName()
	{
		return 'QuestionBloc';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, questions', 'required'),
			array('title', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('title', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'title' => 'Titre',
                        'questions' => 'Questions',
		);
	}

}