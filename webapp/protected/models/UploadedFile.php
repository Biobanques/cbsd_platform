<?php

/**
 * This is the MongoDB Document model class based on table "QuestionBloc".
 */
class UploadedFile extends LoggableActiveRecord {

    public $filename;

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
        return 'UploadedFile';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('filename', 'file', 'types'=>'xml', 'safe' => true)
        );
    }
    
    public function attributeLabels() {
        return array(
            'filename' => Yii::t('common', 'importFile')
        );
    }

}
