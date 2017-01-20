<?php

/**
 * This is the model class for table "tbl_audit_trail".
 */
class AuditTrail extends EMongoDocument {

    /**
     * The followings are the available columns in table 'tbl_audit_trail':
     * @var integer $id
     * @var string $new_value
     * @var string $old_value
     * @var string $action
     * @var string $model
     * @var string $field
     * @var string $stamp
     * @var integer $user_id
     * @var string $model_id
     */
    public $id;
    public $new_value;
    public $old_value;
    public $action;
    public $model;
    public $field;
    public $stamp;
    public $user_id;
    public $model_id;

    /**
     * Returns the static model of the specified AR class.
     * @return AuditTrail the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function getCollectionName() {
        return 'tbl_audit_trail';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('action, model, field, stamp, model_id', 'required'),
            array('action', 'length', 'max' => 255),
            array('model', 'length', 'max' => 255),
            array('field', 'length', 'max' => 255),
            array('model_id', 'length', 'max' => 255),
            array('user_id', 'length', 'max' => 255),
            array('id, new_value, old_value, action, model, field, stamp, user_id, model_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'old_value' => 'Ancienne valeur',
            'new_value' => 'Nouvelle valeur',
            'action' => 'Action',
            'model' => 'Modèle',
            'field' => 'Champs',
            'stamp' => 'Horodatage',
            'user_id' => 'Utilisateur',
            'model_id' => 'Modèle ID',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search($caseSensitive = false) {
        $criteria = new EMongoCriteria;
        if (isset($this->old_value) && !empty($this->old_value))
            $criteria->addCond('old_value', '==', new MongoRegex('/' . $this->old_value . '/i'));
        if (isset($this->new_value) && !empty($this->new_value))
            $criteria->addCond('new_value', '==', new MongoRegex('/' . $this->new_value . '/i'));
        if (isset($this->action) && !empty($this->action))
            $criteria->addCond('action', '==', new MongoRegex('/' . $this->action . '/i'));
        if (isset($this->model) && !empty($this->model))
            $criteria->addCond('model', '==', new MongoRegex('/' . $this->model . '/i'));
        if (isset($this->field) && !empty($this->field))
            $criteria->addCond('field', '==', new MongoRegex('/' . $this->field . '/i'));
        if (isset($this->stamp) && !empty($this->stamp)) {
            $answerFormat = CommonTools::formatDatePicker($this->stamp);
            $date_from = str_replace('/', '-', $answerFormat['date_from']);
            $date_to = str_replace('/', '-', $answerFormat['date_to']);
            $criteria->stamp->date = array('$gte' => date('Y-m-d', strtotime($date_from)) . " 00:00:00.000000", '$lte' => date('Y-m-d', strtotime($date_to)) . " 23:59:59.000000");
        }
        $criteria->sort('stamp', EMongoCriteria::SORT_DESC);
        return new EMongoDocumentDataProvider($this, array(
            'criteria' => $criteria
        ));
    }

    public function scopes() {
        return array(
            'recently' => array(
                'order' => ' t.stamp DESC ',
            ),
        );
    }

    /**
     * get actions.
     */
    public function getActions() {
        $res = array();
        $res ['CREATE'] = "CREATE";
        $res ['SET'] = "SET";
        $res ['CHANGE'] = "CHANGE";
        $res ['DELETE'] = "DELETE";
        return $res;
    }
    
    /**
     * get the timestamp into a french date format JJ/MM/AAAA
     * @return type
     */
    public function getTimestamp() {
        if ($this->stamp != null)
            return date('d/m/Y H:i:s', strtotime($this->stamp['date']));
        else
            return null;
    }

    /**
     * retourne tous les utilisateurs
     * @return type
     */
    public function getAllUsers() {
        $result = array();
        $users = AuditTrail::model()->findAll();
        if ($users != null) {
            foreach ($users as $user) {
                if (!in_array($user->user_id, $result)) {
                    array_push($result, $user->user_id);
                }
            }
        }
        return $result;
    }

}
