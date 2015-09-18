<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class RecoverPwdForm extends CFormModel
{
    public $nom;
    public $prenom;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            array('nom, prenom', 'type', 'type' => 'string')
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'nom' => Yii::t('common', 'lastname'),
            'prenom' => Yii::t('common', 'firstname')
        );
    }

    public function validateFields() {
        $mixedResult = array();
        $result = false;
        $message = '';
        $user = null;
        if (!empty($this->nom) || !empty($this->prenom)) {
            if (!empty($this->nom) && !empty($this->prenom)) {
                $criteria = new EMongoCriteria();
                $criteria->nom = $this->nom;
                $criteria->prenom = $this->prenom;
                $user = User::model()->find($criteria);
                if ($user != null) {
                    $result = true;
                    $message = Yii::t('common', 'recoverMessageSent', array('{userEmail}' => $user->email));
                } else {
                    $result = false;
                    $message = Yii::t('common', 'noUserWithLastnameAndFirstname', array('{badLastname}' => $this->nom, '{badFirstname}' => $this->prenom));
                }
            } elseif (!empty($this->nom)) {
                $criteria = new EMongoCriteria();
                $criteria->nom = $this->nom;
                $user = User::model()->find($criteria);
                if ($user != null) {
                    $result = true;
                    $message = Yii::t('common', 'recoverMessageSent', array('{userEmail}' => $user->email));
                } else {
                    $result = false;
                    $message = Yii::t('common', 'noUserWithLastname', array('{badLastname}' => $this->nom));
                }
            } elseif (!empty($this->prenom)) {
                $criteria = new EMongoCriteria();
                $criteria->prenom = $this->prenom;
                $user = User::model()->find($criteria);
                if ($user != null) {
                    $result = true;
                    $message = Yii::t('common', 'recoverMessageSent', array('{userEmail}' => $user->email));
                } else {
                    $result = false;
                    $message = Yii::t('common', 'noUserWithFirstname', array('{badFirstname}' => $this->prenom));
                }
            }
        } else {
            $result = false;
            $message = Yii::t('common', 'atLeastOneField');
        }
        $mixedResult['user'] = $user;
        $mixedResult['result'] = $result;
        $mixedResult['message'] = $message;
        return $mixedResult;
    }

}