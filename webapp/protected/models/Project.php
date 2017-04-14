<?php

/**
 * Object answer to store a questionnaire definition + answers
 * Copy of object questionnaire to prevent problems of update with questionnaire and forwar compatibility
 * @property integer $id
 * @author Bernard TE
 *
 */
class Project extends EMongoDocument {

    /**
     * 
     */
// This has to be defined in every model, this is same as with standard Yii ActiveRecord
    public $user;
    public $project_name;
    public $file;
    public $project_date;

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
        return 'Project';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('project_name', 'required'),
            // The following rule is used by search().
            array('user, project_name, file, project_date', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'user' => Yii::t('common','login'),
            'project_name' => Yii::t('administration','projectName'),
            'file' => Yii::t('administration','file'),
            'project_date' => Yii::t('administration','projectDate')
        );
    }
    
    public function search($caseSensitive = false) {
        $criteria = new EMongoCriteria;
        Yii::app()->session['criteria'] = $criteria;
        return new EMongoDocumentDataProvider($this, array(
            'criteria' => $criteria
        ));
    }
    
    public function getDateProject() {
        if ($this->project_date != null) {
            return date('d/m/Y H:i', strtotime($this->project_date['date']));
        } else {
            return null;
        }
    }

}

?>