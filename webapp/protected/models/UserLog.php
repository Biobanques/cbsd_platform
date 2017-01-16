<?php

/**
 * Object to store basic user
 * @author nmalservet
 *
 */
class UserLog extends EMongoDocument
{
    public $user;
    public $ipAddress;
    public $profil;
    public $connectionDate;


    // This has to be defined in every model, this is same as with standard Yii ActiveRecord
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    // This method is required!
    public function getCollectionName()
    {
        return 'userLog';
    }

    public function rules()
    {
        $result = array(
            array('user, ipAddress, profil, connectionDate', 'required'),
            array('user, ipAddress, profil, connectionDate', 'safe', 'on' => 'search, update')
        );
        return $result;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'user' => Yii::t('common', 'login'),
            'ipAddress' => 'Adresse IP',
            'profil' => Yii::t('common', 'profile'),
            'connectionDate' => 'Dernière connexion'
        );
    }

    public function search($caseSensitive = false)
    {
        $criteria = new EMongoCriteria;
        if (isset($this->user) && !empty($this->user)) {
            $regex = CommonTools::regexString($this->user);
            $criteriaUser = new EMongoCriteria;
            $criteriaUser->nom = new MongoRegex($regex);
            $criteriaUser->select(array('_id'));
            $users = User::model()->findAll($criteriaUser);
            $listUsers = array();
            if ($users != null) {
                foreach ($users as $user) {
                    $listUsers[] = $user->_id;
                }
            }
            $criteria->addCond('user', 'in', $listUsers);
        }
        if (isset($this->ipAddress) && !empty($this->ipAddress)) {
            $regex = '/';
            foreach ($this->ipAddress as $value) {
                $regex .= $value;
                if ($value != end($this->ipAddress)) {
                    $regex.= '|';
                }
            }
            $regex .= '/i';
            $criteria->addCond('nom', '==', new MongoRegex($regex));
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
        Yii::app()->session['criteria'] = $criteria;
        return new EMongoDocumentDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'login ASC',
            )
        ));
    }
    
    /**
     * get the last connection date into a french date format JJ/MM/AAAA
     * @return type
     */
    public function getConnectionDate() {
        if ($this->connectionDate != null) {
            return date('d/m/Y H:i', strtotime($this->connectionDate['date']));
        } else {
            return null;
        }
    }
    
}