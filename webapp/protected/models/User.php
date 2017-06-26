<?php

/**
 * Object to store basic user
 * @author nmalservet
 *
 */
class User extends LoggableActiveRecord
{
    public $login;
    public $password;
    protected $passwordCompare;
    public $profil;
    public $nom;
    public $prenom;
    public $email;
    protected $emailCompare;
    public $telephone;
    public $gsm;
    public $address;
    public $centre;
    public $registerDate;

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
            array('password', 'compare', 'compareAttribute' => 'passwordCompare', 'on' => 'subscribe'),
            array('passwordCompare', 'safe', 'on' => 'subscribe'),
            array('passwordCompare', 'required', 'on' => 'subscribe'),
            array('email', 'compare', 'compareAttribute' => 'emailCompare', 'on' => 'subscribe'),
            array('emailCompare', 'safe', 'on' => 'subscribe'),
            array('emailCompare', 'required', 'on' => 'subscribe'),
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
            '_id' => Yii::t('common', 'idUser'),
            'prenom' => Yii::t('common', 'firstName'),
            'nom' => Yii::t('common', 'lastName'),
            'login' => Yii::t('common', 'login'),
            'password' => Yii::t('common', 'password'),
            'passwordCompare' => Yii::t('common', 'passwordCompare'),
            'email' => Yii::t('common', 'email'),
            'emailCompare' => Yii::t('common', 'emailCompare'),
            'telephone' => Yii::t('common', 'phone'),
            'gsm' => Yii::t('common', 'gsm'),
            'profil' => Yii::t('common', 'profile'),
            'address' => Yii::t('common', 'address'),
            'centre' => Yii::t('common', 'referenceCentre'),
            'registerDate' => Yii::t('common', 'registerDate')
        );
    }

    public function search($caseSensitive = false)
    {
        $criteria = new EMongoCriteria;
        if (isset($this->login) && !empty($this->login)) {
            $regex = '/';
            foreach ($this->login as $value) {
                $regex .= $value;
                if ($value != end($this->login)) {
                    $regex.= '|';
                }
            }
            $regex .= '/i';
            $criteria->addCond('login', '==', new MongoRegex($regex));
        }
        if (isset($this->profil) && !empty($this->profil)) {
            $regex = '/';
            foreach ($this->profil as $value) {
                $regex .= $value;
                if ($value != end($this->profil)) {
                    $regex.= '|';
                }
            }
            $regex .= '/i';
            $criteria->addCond('profil', '==', new MongoRegex($regex));
        }
        if (isset($this->nom) && !empty($this->nom)) {
            $regex = '/';
            foreach ($this->nom as $value) {
                $regex .= $value;
                if ($value != end($this->nom)) {
                    $regex.= '|';
                }
            }
            $regex .= '/i';
            $criteria->addCond('nom', '==', new MongoRegex($regex));
        }
        if (isset($this->prenom) && !empty($this->prenom)) {
            $regex = '/';
            foreach ($this->prenom as $value) {
                $regex .= $value;
                if ($value != end($this->prenom)) {
                    $regex.= '|';
                }
            }
            $regex .= '/i';
            $criteria->addCond('prenom', '==', new MongoRegex($regex));
        }
        if (isset($this->email) && !empty($this->email)) {
            $regex = '/';
            foreach ($this->email as $value) {
                $regex .= $value;
                if ($value != end($this->email)) {
                    $regex.= '|';
                }
            }
            $regex .= '/i';
            $criteria->addCond('email', '==', new MongoRegex($regex));
        }
        Yii::app()->session['criteria'] = $criteria;
        return new EMongoDocumentDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'login ASC',
            )
        ));
    }
    
    public function getPasswordCompare() {
        return $this->passwordCompare;
    }

    public function setPasswordCompare($value) {
        $this->passwordCompare = $value;
    }
    
    public function getEmailCompare() {
        return $this->emailCompare;
    }

    public function setEmailCompare($value) {
        $this->emailCompare = $value;
    }
    
    /**
     * get all the users lastnames.
     */
    public function getAllUsersLastnames()
    {
        $usersLastNames = array();
        $users = User::model()->findAll();
        if ($users != null) {
            foreach ($users as $user) {
                $usersLastNames[$user->nom] = $user->nom;
            }
        }
        asort($usersLastNames, SORT_NATURAL | SORT_FLAG_CASE);
        return $usersLastNames;
    }
    
    /**
     * get all the users lastnames.
     */
    public function getAllUsersFirstnames()
    {
        $usersFirstNames = array();
        $users = User::model()->findAll();
        if ($users != null) {
            foreach ($users as $user) {
                $usersFirstNames[$user->prenom] = $user->prenom;
            }
        }
        asort($usersFirstNames, SORT_NATURAL | SORT_FLAG_CASE);
        return $usersFirstNames;
    }
    
    /**
     * get all the users login.
     */
    public function getAllUsersLogin()
    {
        $usersLogin = array();
        $users = User::model()->findAll();
        if ($users != null) {
            foreach ($users as $user) {
                $usersLogin[$user->login] = $user->login;
            }
        }
        asort($usersLogin, SORT_NATURAL | SORT_FLAG_CASE);
        return $usersLogin;
    }
    
     /**
     * return all the profiles from an user.
     */
    public function getAllProfilesUser($userLogin)
    {
        $profilesUser = "";
        $user = User::model()->findByAttributes(array('login' => $userLogin));
        if ($user != null) {
            $profilesUser = implode(", ", $user->profil);
        }
        return $profilesUser;
    }
    
    /**
     * get all the users email.
     */
    public function getAllUsersEmail()
    {
        $usersEmail = array();
        $users = User::model()->findAll();
        if ($users != null) {
            foreach ($users as $user) {
                $usersEmail[$user->email] = $user->email;
            }
        }
        asort($usersEmail, SORT_NATURAL | SORT_FLAG_CASE);
        return $usersEmail;
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
        $res ['Clinicien'] = Yii::t('profile', 'Clinicien');
        $res ['Administrateur'] = Yii::t('profile', 'Administrateur');
        $res ['Administrateur de projet'] = Yii::t('profile', 'Administrateur du projet');
        $res ['Neuropathologiste'] = Yii::t('profile', 'Neuropathologiste');
        $res ['Généticien'] = Yii::t('profile', 'Généticien');
        $res ['Chercheur'] = Yii::t('profile', 'Chercheur');
        $res ['Clinicien Master'] = Yii::t('profile', 'Clinicien Master');
        $res ['Neuropathologique Master'] = Yii::t('profile', 'Neuropathologiste Master');
        $res ['Généticien Master'] = Yii::t('profile', 'Généticien Master');
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
            if ($value == "Administrateur") {
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
        $user = array();
        foreach ($users->profil as $u) {
            $user[$u] = ucfirst($u);
        }
        return array_diff($this->getArrayProfilFiltered(), $user);
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
    
    public function getRegisterDate() {
        return CommonTools::formatDateFR(User::model()->findByPk(new MongoId(Yii::app()->user->id))->registerDate['date']);
    }
    
    public function beforeSave()
    {
        if (Yii::app()->controller->action->id == "subscribe") {
            $this->profil = [];
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
            $this->addError('telephone', Yii::t('common', 'insertPhone'));
        }
        if (!in_array($this->telephone, array("", null))) {
            if (!preg_match("/^0[1-9][0-9]{8}$/i", $this->telephone)) {
                $this->addError('telephone', Yii::t('common', 'unvalidPhone'));
            }
        }
    }

    public function gsmValidator()
    {
        if (!in_array($this->gsm, array("", null))) {
            if (!preg_match("/^0[1-9][0-9]{8}$/i", $this->gsm)) {
                $this->addError('gsm', Yii::t('common', 'unvalidMobilePhone'));
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
            if (in_array("Clinicien", $this->profil) && ($this->address == "")) {
                $this->validatorList->add(CValidator::createValidator('required', $this, 'address', array()));
                $this->addError('address', Yii::t('common', 'addressClinician'));
            }
        }
    }

    public function centreValidator()
    {
        if (isset($this->profil)) {
            if (gettype($this->profil) == "string") {
                
            } else
            if (in_array("Neuropathologiste", $this->profil) && ($this->centre == "")) {
                $this->validatorList->add(CValidator::createValidator('required', $this, 'centre', array()));
                $this->addError('centre', Yii::t('common', 'centerNeuropathologist'));
            }
        }
    }

    public function getDefaultProfil()
    {
        if (in_array("Administrateur", $this->profil)) {
            return "Administrateur";
        }
        if (in_array("Neuropathologiste", $this->profil)) {
            return "Neuropathologiste";
        }
        if (in_array("Généticien", $this->profil)) {
            return "Généticien";
        }
        if (in_array("Clinicien", $this->profil)) {
            return "Clinicien";
        }
        if (in_array("Chercheur", $this->profil)) {
            return "Chercheur";
        }
        if (in_array("Clinicien Master", $this->profil)) {
            return "Clinicien Master";
        }
        if (in_array("Neuropathologiste Master", $this->profil)) {
            return "Neuropathologiste Master";
        }
        if (in_array("Généticien Master", $this->profil)) {
            return "Généticien Master";
        }
    }
    
    public function getAllEmailsAdmin() {
        $emails = array();
        $users = User::model()->findAllByAttributes(array('profil'=> 'Administrateur'));
        if ($users != null) {
            foreach ($users as $key) {
                array_push($emails, $key->email);
            }
        }
        return $emails;
    }
}