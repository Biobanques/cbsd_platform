<?php

/**
 * This is the MongoDB Document model class based on table "patient".
 */
class PatientForm extends CFormModel {

    public $id;
    public $nom;
    public $prenom;
    public $date_naissance;
    public $nom_naissance;
    public $sexe;
    
    /**
     * Returns the static model of the specified AR class.
     * @return Patient the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('nom, prenom, nom_naissance', 'required'),
            array('nom, prenom, nom_naissance', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('nom, prenom, nom_naissance', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'nom' => 'Nom',
            'prenom' => 'Prénom',
            'date_naissance' => 'Date de naissance',
            'nom_naissance' => 'Nom de naissance',
            'sexe' => 'Sexe',        
        );
    }

}
