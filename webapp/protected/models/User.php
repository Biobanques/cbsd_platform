<?php

/**
 * Object to store basic user
 * @author nmalservet
 *
 */
class User extends EMongoDocument
{
    public $login;
    public $password;
    public $profil;
    public $nom;
    public $prenom;
    public $email;
    public $telephone;
    public $gsm;
    public $address;
    public $centre;

    // This has to be defined in every model, this is same as with standard Yii ActiveRecord
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    // This method is required!
    public function getCollectionName()
    {
        return 'user';
    }

    public function rules()
    {
        $result = array(
            array('gsm, telephone', 'numerical', 'integerOnly' => true),
            array('prenom, nom, login, password, email', 'length', 'max' => 250),
            array('telephone', 'telValidator'),
            array('gsm', 'gsmValidator'),
            array('prenom, nom, login, password, email, profil, telephone', 'required'),
            array('email', 'CEmailValidator', 'allowEmpty' => false),
            array('login', 'EMongoUniqueValidator', 'on' => 'subscribe,create'),
            array('address', 'addressValidator'),
            array('centre', 'centreValidator'),
            array('password', 'passwordValidator'),
            array('password', 'length', 'min' => 6),
            array('prenom, nom, login, password, email, telephone, gsm, profil, address, centre', 'safe', 'on' => 'search, update')
        );
        return $result;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            '_id' => 'ID',
            'prenom' => Yii::t('common', 'firstname'),
            'nom' => Yii::t('common', 'lastname'),
            'login' => Yii::t('common', 'Login'),
            'password' => Yii::t('common', 'password'),
            'email' => Yii::t('common', 'email'),
            'telephone' => Yii::t('common', 'phone'),
            'gsm' => Yii::t('common', 'gsm'),
            'profil' => 'Profil',
            'address' => 'Adresse',
            'centre' => 'Centre de référence'
        );
    }

    public function search($caseSensitive = false)
    {
        $criteria = new EMongoCriteria ();
        if (isset($this->login) && !empty($this->login)) {
            $criteria->addCond('login', '==', new MongoRegex('/' . $this->login . '/i'));
        }
        if (isset($this->profil) && !empty($this->profil)) {
            $criteria->addCond('profil', '==', new MongoRegex('/' . $this->profil . '/i'));
        }
        if (isset($this->nom) && !empty($this->nom)) {
            $criteria->addCond('nom', '==', new MongoRegex('/' . $this->nom . '/i'));
        }
        if (isset($this->prenom) && !empty($this->prenom)) {
            $criteria->addCond('prenom', '==', new MongoRegex('/' . $this->prenom . '/i'));
        }
        Yii::app()->session['criteria'] = $criteria;
        return new EMongoDocumentDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'login ASC',
            )
        ));
    }
    
    /**
     * get all users by login.
     */
    public function getAllUsersByLogin($model)
    {
        $criteria = new EMongoCriteria();
        $criteria->login = $model->login;
        $userLogin = User::model()->findAll($criteria);
        return $userLogin;
    }

    /**
     * profils : 0: clinicien, 1 : administrateur, 2: neuropathologiste, 3: généticien
     * @return type
     */
    public function getProfil()
    {
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
     * get an array of profil used by dropDownLIst.
     */
    public function getArrayProfil()
    {
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
    public function getArrayProfilSorted()
    {
        $resArraySorted = new ArrayObject($this->getArrayProfil());
        $resArraySorted->asort();
        return $resArraySorted;
    }

    /**
     * get an array filtered.
     */
    public function getArrayProfilFiltered()
    {
        $resArrayFiltered = (array) $this->getArrayProfilSorted();
        foreach ($resArrayFiltered AS $key => $value) {
            if ($value == "administrateur") {
                unset($resArrayFiltered[$key]);
            }
        }
        return $resArrayFiltered;
    }

    /**
     * get an array of available profils.
     */
    public function getArrayAvailableProfil($user)
    {
        $users = User::model()->findByPk(new MongoID($user));
        return array_diff($this->getArrayProfilFiltered(), $users->profil);
    }

    /**
     * get an array of centre used by dropDownLIst.
     */
    public function getArrayCentre()
    {
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
    
    public function beforeSave()
    {
        if (Yii::app()->controller->action->id == "subscribe") {
            if (in_array("neuropathologiste", $this->profil) || in_array("geneticien", $this->profil) || in_array("chercheur", $this->profil)) {
            $this->profil = [];
            }
        }
        return parent::beforeSave();
    }

    /**
     * Custom validation rules
     */
    public function passwordValidator()
    {
        $nbDigit = 0;
        $length = strlen($this->password);
        for ($i = 0; $i < $length; $i++) {
            if (is_numeric($this->password[$i])) {
                $nbDigit++;
            }
        }
        if ($nbDigit < 2 && $this->password != "") {
            $this->addError('password', Yii::t('common', 'notEnoughDigits'));
        }
    }

    public function telValidator()
    {
        if (in_array($this->telephone, array("", null)) && in_array($this->gsm, array("", null))) {
            $this->addError('telephone', 'Veuillez renseigner au moins un numéro de téléphone.');
        }
        if (!in_array($this->telephone, array("", null))) {
            if (!preg_match("/^0[1-9][0-9]{8}$/i", $this->telephone)) {
                $this->addError('telephone', 'Le numéro de téléphone que vous avez renseigné n\'est pas valide (format 01 02 03 04 05).');
            }
        }
    }

    public function gsmValidator()
    {
        if (!in_array($this->gsm, array("", null))) {
            if (!preg_match("/^0[1-9][0-9]{8}$/i", $this->gsm)) {
                $this->addError('gsm', 'Le numéro de téléphone portable que vous avez renseigné n\'est pas valide (format 01 02 03 04 05).');
            }
        }
    }

    /**
     * Alphabetic case unsensitive characters, including accentued characters, spaces and '-' only.
     */
    public function alphaOnly()
    {
        if (!preg_match("/^[a-zàâçéèêëîïôûùüÿñæœ -]*$/i", $this->nom)) {
            $this->addError('nom', Yii::t('common', 'onlyAlpha'));
        }
        if (!preg_match("/^[a-zàâçéèêëîïôûùüÿñæœ -]*$/i", $this->prenom)) {
            $this->addError('prenom', Yii::t('common', 'onlyAlpha'));
        }
    }

    /**
     * Alphabetic case unsensitive characters, including accentued characters, spaces and '-' only. + numeric
     */
    public function alphaNumericOnly()
    {
        if (!preg_match("/^[a-zàâçéèêëîïôûùüÿñæœ0-9 -]*$/i", $this->login)) {
            $this->addError('login', Yii::t('common', 'onlyAlphaNumeric'));
        }
    }

    public function addressValidator()
    {
        if (isset($this->profil)) {
            if (gettype($this->profil) == "string") {
                
            } else
            if (in_array("clinicien", $this->profil) && ($this->address == "")) {
                $this->validatorList->add(CValidator::createValidator('required', $this, 'address', array()));
                $this->addError('address', 'L\'adresse doit être renseignée par le clinicien.');
            }
        }
    }

    public function centreValidator()
    {
        if (isset($this->profil)) {
            if (gettype($this->profil) == "string") {
                
            } else
            if (in_array("neuropathologiste", $this->profil) && ($this->centre == "")) {
                $this->validatorList->add(CValidator::createValidator('required', $this, 'centre', array()));
                $this->addError('centre', 'Le centre de référence doit être renseigné pour le neuropathologiste.');
            }
        }
    }

    public function getDefaultProfil()
    {
        if (in_array("administrateur", $this->profil)) {
            return "administrateur";
        }
        if (in_array("neuropathologiste", $this->profil)) {
            return "neuropathologiste";
        }
        if (in_array("geneticien", $this->profil)) {
            return "geneticien";
        }
        if (in_array("clinicien", $this->profil)) {
            return "clinicien";
        }
        if (in_array("chercheur", $this->profil)) {
            return "chercheur";
        }
    }
}