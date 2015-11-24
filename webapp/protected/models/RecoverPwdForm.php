<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class RecoverPwdForm extends CFormModel {

    public $login;
    public $email;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            array('login, email', 'required')
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'login' => "Nom d'utilisateur",
            'email' => "Adresse email"
        );
    }

    public function validateFields() {
        $mixedResult = array();
        $result = false;
        $message = '';
        $user = null;
        if (!empty($this->login) || !empty($this->email)) {
            if (!empty($this->login) && !empty($this->email)) {
                $criteria = new EMongoCriteria();
                $criteria->login = $this->login;
                $criteria->email = $this->email;
                $user = User::model()->find($criteria);
                if ($user != null) {
                    $result = true;
                    $message = Yii::t('common', 'recoverMessageSent', array('{userEmail}' => $user->email));
                } else {
                    $result = false;
                    $message = Yii::t('common', 'noUserWithLoginAndEmail', array('{badLogin}' => $this->login, '{badEmail}' => $this->email));
                }
            } elseif (!empty($this->login)) {
                $criteria = new EMongoCriteria();
                $criteria->login = $this->login;
                $user = User::model()->find($criteria);
                if ($user != null) {
                    $result = true;
                    $message = Yii::t('common', 'recoverMessageSent', array('{userEmail}' => $user->email));
                } else {
                    $result = false;
                    $message = Yii::t('common', 'noUserWithLogin', array('{badLogin}' => $this->login));
                }
            } elseif (!empty($this->email)) {
                $criteria = new EMongoCriteria();
                $criteria->email = $this->email;
                $user = User::model()->find($criteria);
                if ($user != null) {
                    $result = true;
                    $message = Yii::t('common', 'recoverMessageSent', array('{userEmail}' => $user->email));
                } else {
                    $result = false;
                    $message = Yii::t('common', 'noUserWithEmail', array('{badEmail}' => $this->email));
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
