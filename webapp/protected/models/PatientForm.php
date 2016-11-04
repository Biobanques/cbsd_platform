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
    public $source;
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
            array('nom_naissance, prenom, date_naissance, nom, sexe, source', 'required', 'on'=>'create'),
            array('date_naissance', 'dateFormat'),
            array('nom_naissance, prenom', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('nom_naissance, prenom, date_naissance, nom, sexe, source, action', 'safe'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'nom' => 'Nom d\'usage',
            'prenom' => Yii::t('common', 'firstName'),
            'date_naissance' => Yii::t('common', 'birthDate'),
            'nom_naissance' => Yii::t('common', 'birthName'),
            'sexe' => Yii::t('common', 'sex'),
            'source' => 'Source'
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
    
    public function copyPatient($patient) {
        $this->id = $patient->id;
        $this->nom = $patient->useName;
        $this->prenom = $patient->firstName;
        $this->nom_naissance = $patient->birthName;
        $this->date_naissance = $patient->birthDate;
    }

    public function getGenre() {
        $res = array();
        $res ['M'] = Yii::t('common', 'man');
        $res ['F'] = Yii::t('common', 'woman');
        $res ['U'] = Yii::t('common', 'unknown');
        return $res;
    }

    public function getSource() {
        $res = array();
        $res ['1'] = "bb_cerveau";
        $res ['2'] = "bb_adn";
        return $res;
    }

}
