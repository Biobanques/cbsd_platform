<?php

/**
 * Object answer to store a questionnaire definition + answers
 * Copy of object questionnaire to prevent problems of update with questionnaire and forwar compatibility
 * @property integer $id
 * @author nmalservet
 *
 */
class Droits extends LoggableActiveRecord {

    /**
     * 
     */
// This has to be defined in every model, this is same as with standard Yii ActiveRecord
    public $profil;
    public $type;
    public $role;

    /**
     * Returns the static model of the specified AR class.
     * @return QuestionBloc the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated collection name
     */
    public function getCollectionName() {
        return 'Droits';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('profil', 'required'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('profil, role, _id', 'safe', 'on' => 'search,update'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'profil' => 'Profil',
            'role' => 'Role'
        );
    }

}

?>