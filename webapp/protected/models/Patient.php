<?php

/**
 * This is the MongoDB Document model class based on table "patient".
 */
class Patient extends EMongoDocument
{
	public $nom;
	public $prenom;
	public $date_naissance;
	public $nom_naissance;

	/**
	 * Returns the static model of the specified AR class.
	 * @return Patient the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * returns the primary key field for this model
	 */
	public function primaryKey()
	{
		return NULL;
	}

	/**
	 * @return string the associated collection name
	 */
	public function getCollectionName()
	{
		return 'patient';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nom, prenom, date_naissance, nom_naissance', 'required'),
			array('nom, prenom, nom_naissance', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('nom, prenom, date_naissance, nom_naissance', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'nom' => 'Nom',
			'prenom' => 'Prenom',
			'date_naissance' => 'Date Naissance',
			'nom_naissance' => 'Nom Naissance',
		);
	}
}