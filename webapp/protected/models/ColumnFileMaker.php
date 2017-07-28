<?php

/**
 * Object to store basic user
 * @author nmalservet
 *
 */
class ColumnFileMaker extends LoggableActiveRecord
{
    public $currentColumn;
    public $newColumn;
    public $type;


    // This has to be defined in every model, this is same as with standard Yii ActiveRecord
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    // This method is required!
    public function getCollectionName()
    {
        return 'columnFileMaker';
    }

    public function rules()
    {
        $result = array(
            array('currentColumn, newColumn, type', 'required'),
            array('currentColumn, newColumn, type', 'safe', 'on' => 'search, update')
        );
        return $result;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'currentColumn' => Yii::t('common', 'fileMakerColumn'),
            'newColumn' => Yii::t('common', 'cbsdColumn'),
            'type' => 'Type'
        );
    }

    public function search($caseSensitive = false)
    {
        $criteria = new EMongoCriteria;
        Yii::app()->session['criteria'] = $criteria;
        return new EMongoDocumentDataProvider($this, array(
            'criteria' => $criteria
        ));
    }
    
    public function getTypesQuestions() {
        return Question::model()->getArrayTypes();
    }
    
    public function getType() {
        return $this->getTypesQuestions()[$this->type];
    }
    
    public function getCurrentColumnByNewColumn($columnCBSD) {
        return ColumnFileMaker::model()->findByAttributes(array('newColumn' => $columnCBSD))->currentColumn;
    }

}