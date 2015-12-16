<?php

/**
 * This is the MongoDB Document model class based on table "patient".
 */
class PatientForm extends CFormModel
{
    public $id;
    public $nom;
    public $prenom;
    public $date_naissance;
    public $nom_naissance;
    public $sexe;
    public $action;

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
            array('nom_naissance, prenom, date_naissance', 'required'),
            array('date_naissance', 'dateFormat'),
            array('nom_naissance, prenom', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('nom_naissance, prenom, date_naissance, nom, sexe,action', 'safe'),
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
            'sexe' => 'Genre',
        );
    }

    public function dateFormat($date_naissance) {
        if (preg_match("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", $date_naissance, $matches)) {
            if (!checkdate($matches[2], $matches[1], $matches[3])) {
                return false;
            }
        } else {
            return false;
        }
        return true;
    }

}