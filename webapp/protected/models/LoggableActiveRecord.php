<?php

/**
 * classe qui ented l active record et ajoute le comprtement loggable utile pour catcher les actions effectuées sur la base
 * @author nicolas
 *
 */
abstract class LoggableActiveRecord extends EMongoSoftDocument
{

    /**
     * ajout du comportement pour log audittrail
     * @return multitype:string
     */
   /* public function behaviors() {
        return array(
            'LoggableBehavior' =>
            'application.modules.auditTrail.behaviors.LoggableBehavior',
        );
    }*/
    
    /*$
     * *
     * todo action before save to save old attribute sinto an instance
     */
    public function afterSave() {
        parent::afterSave();
        Yii::log("go on aftersave", CLogger::LEVEL_ERROR);
         Yii::log("go on loggable behaviors", CLogger::LEVEL_ERROR);
        try {
            $username = Yii::app()->user->nom;
            $userid = Yii::app()->user->_id;
        } catch (Exception $e) { //If we have no user object, this must be a command line program
            $username = 'NO_USER';
            $userid = null;
        }

        if (empty($username)) {
            $username = 'NO_USER';
        }

        if (empty($userid)) {
            $userid = null;
        }

        $newattributes = $this->getAttributes();
        $oldattributes = $this->getOldAttributes();

        if (!$this->isNewRecord) {
            // compare old and new
            foreach ($newattributes as $name => $value) {
                if (!empty($oldattributes) && isset($oldattributes[$name])) {
                    $old = $oldattributes[$name];
                } elseif (!empty($oldattributes) && !isset($oldattributes[$name])) {
                    $old = 'Undefined : new attribute';
                } else {
                    $old = '';
                }

                if ($value != $old) {
//                if (is_string($value) && ($value != $old)) {
                    $log = new AuditTrail();

                    $log->old_value = $old;

                    if (is_string($value))
                        $log->new_value = $value;
                    else {

                        $log->new_value = json_decode(json_encode($value));
                    }
                    $log->action = 'CHANGE';
                    $log->model = get_class($this->Owner);
                    $log->model_id = $this->Owner->getPrimaryKey();
                    $log->field = $name;
                    $log->stamp = date('Y-m-d H:i:s');
                    $log->user_id = $userid;

                    $log->save();
                }
            }
        } else {
            $log = new AuditTrail();
            $log->old_value = '';
            $log->new_value = '';
            $log->action = 'CREATE';
            $log->model = get_class($this->Owner);
            $log->model_id = $this->Owner->_id;
            $log->field = 'N/A';
            $log->stamp = date('Y-m-d H:i:s');
            $log->user_id = $userid;

            $log->save();


            foreach ($newattributes as $name => $value) {
                $log = new AuditTrail();
                $log->old_value = '';
                if (is_string($value))
                    $log->new_value = $value;
                else {

                    $log->new_value = json_decode(json_encode($value));
                }
                $log->action = 'SET';
                $log->model = get_class($this->Owner);
                $log->model_id = $this->Owner->getPrimaryKey();
                $log->field = $name;
                $log->stamp = date('Y-m-d H:i:s');
                $log->user_id = $userid;
                $log->save();
            }
        }
    }

    /**
     * CUSTOM VALIDATION RULES
     */

    /**
     * Alphabetic case unsensitive characters, including accentued characters, spaces and '-' only.
     */
    public function alphaOnly($attribute) {

        if (!preg_match("/^[a-zàâçéèêëîïôûùüÿñæœ -]*$/i", $this->$attribute))
            $this->addError($this->$attribute, Yii::t('common', 'onlyAlpha'));
    }

    /**
     * Alphabetic case unsensitive characters, including accentued characters, spaces and '-' only. + numeric
     */
    public function alphaNumericOnly($attribute) {
        if (!preg_match("/^[a-zàâçéèêëîïôûùüÿñæœ0-9 -]*$/i", $this->$attribute))
            $this->addError($this->$attribute, Yii::t('common', 'onlyAlphaNumeric'));
    }

    /**
     * phone validator. format : +33010203045
     * @param type $attribute
     * @param type $params
     */
    public function phoneValidator($attribute, $params) {
        if (!preg_match("#^\+33[0-9]{9}$#", $this->$attribute))
            $this->addError($this->$attribute, Yii::t('common', 'InvalidPhoneNumber'));
    }

    public function getShortValue($attribute) {
        return CommonTools::getShortValue($this->$attribute);
    }

}
?>