<?php

/**
 * Object answer to store a questionnaire definition + answers
 * Copy of object questionnaire to prevent problems of update with questionnaire and forwar compatibility
 * @property integer $id
 * @author Bernard TE
 *
 */
class Query extends EMongoDocument {

    /**
     * 
     */
// This has to be defined in every model, this is same as with standard Yii ActiveRecord
    public $id_patient;
    public $type;
    public $last_updated;
    public $dynamics;
    public $html;
    public $htmlQuestion;

    /**
     * Returns the static model of the specified AR class.
     * @return FileImport the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated collection name
     */
    public function getCollectionName() {
        return 'Query';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            // The following rule is used by search().
            array('id_patient, type, last_updated, dynamics, html, htmlQuestion', 'safe'),
        );
    }

}

?>