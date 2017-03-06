<?php

class LoggableBehavior extends CActiveRecordBehavior {

    private $_oldattributes = array();

    public function afterSave($event) {
        Yii::log("go on loggable behaviors", CLogger::LEVEL_ERROR);
        try {
            $userid = Yii::app()->user->getNomPrenom();
        } catch (Exception $e) { //If we have no user object, this must be a command line program
            $userid = null;
        }

        if (empty($userid)) {
            $userid = 'NO_USER';
        }

        $newattributes = $this->Owner->getAttributes();
        $oldattributes = $this->getOldAttributes();

        if (!$this->Owner->isNewRecord) {
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
                    $log = new AuditTrail();
                    $log->old_value = $old;
                    if (is_string($value)) {
                        $log->new_value = $value;
                    }else {
                        $log->new_value = json_decode(json_encode($value));
                    }
                    $log->action = 'CHANGE';
                    $log->model = get_class($this->Owner);
                    if (is_array($this->Owner->getPrimaryKey())) {
                        $modelId = implode(", ", $this->Owner->getPrimaryKey());
                    } else {
                        $modelId = $this->Owner->getPrimaryKey();
                    }
                    $log->model_id = $modelId;
                    $log->field = $name;
                    $log->stamp = DateTime::createFromFormat(CommonTools::FRENCH_SHORT_DATE_FORMAT, date(CommonTools::FRENCH_SHORT_DATE_FORMAT));
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
            $log->stamp = DateTime::createFromFormat(CommonTools::FRENCH_SHORT_DATE_FORMAT, date(CommonTools::FRENCH_SHORT_DATE_FORMAT));
            $log->user_id = $userid;
            $log->save();

            foreach ($newattributes as $name => $value) {
                $log = new AuditTrail();
                $log->old_value = '';
                if (is_string($value)) {
                    $log->new_value = $value;
                } else {
                    $log->new_value = json_decode(json_encode($value));
                }
                $log->action = 'SET';
                $log->model = get_class($this->Owner);
                if (is_array($this->Owner->getPrimaryKey())) {
                    $modelId = implode(", ", $this->Owner->getPrimaryKey());
                } else {
                    $modelId = $this->Owner->getPrimaryKey();
                }
                $log->model_id = $modelId;
                $log->field = $name;
                $log->stamp = DateTime::createFromFormat(CommonTools::FRENCH_SHORT_DATE_FORMAT, date(CommonTools::FRENCH_SHORT_DATE_FORMAT));
                $log->user_id = $userid;
                $log->save();
            }
        }
        return parent::afterSave($event);
    }

    public function afterDelete($event) {

        try {
            $userid = Yii::app()->user->getNomPrenom();
        } catch (Exception $e) {
            $userid = null;
        }

        if (empty($userid)) {
            $userid = null;
        }

        $log = new AuditTrail();
        $log->old_value = '';
        $log->new_value = '';
        $log->action = 'DELETE';
        $log->model = get_class($this->Owner);
        if (is_array($this->Owner->getPrimaryKey())) {
            $modelId = implode(", ", $this->Owner->getPrimaryKey());
        } else {
            $modelId = $this->Owner->getPrimaryKey();
        }
        $log->model_id = $modelId;
        $log->field = 'N/A';
        $log->stamp = DateTime::createFromFormat(CommonTools::FRENCH_SHORT_DATE_FORMAT, date(CommonTools::FRENCH_SHORT_DATE_FORMAT));
        $log->user_id = $userid;
        $log->save();
        return parent::afterDelete($event);
    }

    public function afterFind($event) {
        // Save old values
        $this->setOldAttributes($this->Owner->getAttributes());

        return parent::afterFind($event);
    }

    public function getOldAttributes() {
        return $this->_oldattributes;
    }

    public function setOldAttributes($value) {
        $this->_oldattributes = $value;
    }

}

?>