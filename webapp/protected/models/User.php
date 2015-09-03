<?php

/**
 * Object to store basic user
 * @author nmalservet
 *
 */
class User extends EMongoDocument {

    /**
     * 
     */
    public $login;
    /**
     *embedded document with array of QuestionAnswer
     * @var type 
     */
    public $password;
    
    public $profil;
    
    public $nom;
    
    public $prenom;
    
    public $email;
    
    public $tel;

    // This has to be defined in every model, this is same as with standard Yii ActiveRecord
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    // This method is required!
    public function getCollectionName() {
        return 'user';
    }

    public function rules() {
        return array(
            array('login, password, profil, nom, prenom, email, tel', 'required'),
            array(
                'login',
                'safe',
                'on' => 'search'
            )
        );
    }

       /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'login' => Yii::t('common', 'Login'),
            'password' => Yii::t('common', 'password'),
        );
    }


    public function search($caseSensitive = false) {
        $criteria = new EMongoCriteria ();
        if (isset($this->login) && !empty($this->login)) {
            $criteria->login = "" . $this->login . "";
        }

        Yii::app()->session['criteria'] = $criteria;
        return new EMongoDocumentDataProvider($this, array(
            'criteria' => $criteria
        ));
    }
  
    /**
     * profils : 0: clinicien, 1 : administrateur, 2: neuropathologiste, 3: généticien
     * @return type
     */
    public function getProfil() {
        $result = $this->profil;
        $arr = $this->getArrayProfil();
        if ($result != "" && $arr [$result] != null) {
            $result = $arr [$result];
        } else {
            $result = "Not defined";
        }
        return $result;
    }

    public function getArrayProfil() {
        $res = array();
        $res ['0'] = "clinicien";
        $res ['1'] = "administrateur";
        $res ['2'] = "neuropathologiste";
        $res ['3'] = "généticien";
        return $res;
    }

}

?>