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
    
    public $telephone;
    
    public $gsm;
    
    public $address;
    
    public $centre;
    
    public $statut;
    
    public $verifyCode;

    // This has to be defined in every model, this is same as with standard Yii ActiveRecord
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    // This method is required!
    public function getCollectionName() {
        return 'user';
    }

    public function rules() {
        $result = array(
            array('verifyCode', 'CaptchaExtendedValidator', 'allowEmpty' => false, 'on' => 'subscribe'),
            array('gsm, telephone', 'numerical', 'integerOnly' => true),
            array('prenom, nom, login, password, email', 'length', 'max' => 250),
            array('login', 'telPresent'),
            array('gsm, telephone', 'length', 'min' => 8),
            array('prenom, nom, login, password, email, profil, telephone', 'required'),
            array('email', 'CEmailValidator', 'allowEmpty' => false),
            array('login', 'EMongoUniqueValidator', 'on' => 'subscribe,create'),
            array('address', 'addressValidator'),
            array('centre', 'centreValidator'),
            array('password', 'pwdStrength'),
            array('password', 'length', 'min' => 6),
            array('prenom, nom, login, password, email, telephone, gsm, profil, address, centre, statut', 'safe', 'on' => 'search, update'),
        );
        return $result;
    }

       /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            '_id' => 'ID',
            'prenom' => Yii::t('common', 'firstname'),
            'nom' => Yii::t('common', 'lastname'),
            'login' => Yii::t('common', 'Login'),
            'password' => Yii::t('common', 'password'),
            'email' => Yii::t('common', 'email'),
            'telephone' => Yii::t('common', 'phone'),
            'gsm' => Yii::t('common', 'gsm'),
            'profil' => Yii::t('common', 'profil'),
            'address' => 'Adresse',
            'centre' => 'Centre de référence',
            'statut' => 'Statut',
            'verifyCode' => Yii::t('common', 'verifyCode'),
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
    

    /**
     * @return type
     */
    public function getStatut() {
        $result = $this->statut;
        $arr = $this->getArrayStatut();
        if ($result != "" && $arr [$result] != null) {
            $result = $arr [$result];
        } else {
            $result = "Not defined";
        }
        return $result;
    }

    /**
     * get an array of profil used by dropDownLIst.
     */
    public function getArrayProfil() {
        $res = array();
        $res ['clinicien'] = "clinicien";
        $res ['administrateur'] = "administrateur";
        $res ['neuropathologiste'] = "neuropathologiste";
        $res ['geneticien'] = "geneticien";
        $res ['chercheur'] = "chercheur";

        return $res;
    }
    
    /**
     * get an array sorted by value.
     */
    public function getArrayProfilSorted() {
        $resArraySorted = new ArrayObject($this->getArrayProfil());
        $resArraySorted->asort();
        return $resArraySorted;
    }
    
    /**
     * get an array filtered.
     */
    public function getArrayProfilFiltered() {
        $resArrayFiltered = (array) $this->getArrayProfilSorted();
        foreach ($resArrayFiltered AS $key => $value) {
            if ($value == "administrateur")
                unset($resArrayFiltered[$key]);
        }
        return $resArrayFiltered;
    }
    
    /**
     * get an array of statut
     */
    public function getArrayStatut() {
        $res = array();
        $res ['actif'] = "actif";
        $res ['inactif'] = "inactif";
        return $res;
    }
    
    /**
     * get an array of centre used by dropDownLIst.
     */
    public function getArrayCentre() {
        $res = array();
        $res ['Bordeaux'] = "Bordeaux";
        $res ['Caen'] = "Caen";
        $res ['Clermont-Ferrand'] = "Clermont-Ferrand";
        $res ['Lille'] = "Lille";
        $res ['Lyon'] = "Lyon";
        $res ['Marseille'] = "Marseille";
        $res ['Montpellier'] = "Montpellier";
        $res ['Nice'] = "Nice";
        $res ['Paris'] = "Paris";
        $res ['Poitiers'] = "Poitiers";
        $res ['Rennes'] = "Rennes";
        $res ['Rouen'] = "Rouen";
        $res ['Strasbourg'] = "Strasbourg";
        $res ['Toulouse'] = "Toulouse";

        return $res;
    }

    /**
     * Custom validation rules
     */
    public function pwdStrength() {
        $nbDigit = 0;
        $length = strlen($this->password);
        for ($i = 0; $i < $length; $i++) {
            if (is_numeric($this->password[$i]))
                $nbDigit++;
        }
        if ($nbDigit < 2 && $this->password != "")
            $this->addError('password', Yii::t('common', 'notEnoughDigits'));
    }
    public function telPresent() {
        if (in_array($this->telephone, array("", null)) && in_array($this->gsm, array("", null)))
            $this->addError('gsm', 'Au moins un numéro de téléphone');
    }
    /**
     * Alphabetic case unsensitive characters, including accentued characters, spaces and '-' only.
     */
    public function alphaOnly() {
        if (!preg_match("/^[a-zàâçéèêëîïôûùüÿñæœ -]*$/i", $this->nom))
            $this->addError('nom', Yii::t('common', 'onlyAlpha'));
        if (!preg_match("/^[a-zàâçéèêëîïôûùüÿñæœ -]*$/i", $this->prenom))
            $this->addError('prenom', Yii::t('common', 'onlyAlpha'));
    }
    /**
     * Alphabetic case unsensitive characters, including accentued characters, spaces and '-' only. + numeric
     */
    public function alphaNumericOnly() {
        if (!preg_match("/^[a-zàâçéèêëîïôûùüÿñæœ0-9 -]*$/i", $this->login))
            $this->addError('login', Yii::t('common', 'onlyAlphaNumeric'));
    }
    
    public function addressValidator() {
        if (($this->profil == "clinicien") && ($this->address == "")) {
            $this->validatorList->add(CValidator::createValidator('required',$this,'address',array()));
            $this->addError('address', 'Adresse ne peut pas être vide.');
        }
    }
    
    public function centreValidator() {
        if (($this->profil == "neuropathologiste") && ($this->centre == "")) {
            $this->validatorList->add(CValidator::createValidator('required',$this,'centre',array()));
            $this->addError('centre', 'Centre ne peut pas être vide.');
        }
    }

    
}

?>
