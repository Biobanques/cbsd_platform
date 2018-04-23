<?php

class Tranche extends EMongoSoftDocument
{
    public $id;
    public $id_donor;
    public $originSamplesTissue;
    public $quantityAvailable;
    public $storageConditions;
    
    // This has to be defined in every model, this is same as with standard Yii ActiveRecord
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    // This method is required!
    public function getCollectionName()
    {
        return 'Tranche';
    }
    
    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(
                'id,id_donor,originSamplesTissue,quantityAvailable,storageConditions',
                'safe',
                'on' => 'search'
            )
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'Identifiant du prélèvement',
            'id_donor' => "Identifiant du donneur",
            'originSamplesTissue' => "Origin Samples Tissue",
            'quantityAvailable' => "Quantity available",
            'storageConditions' => "Storage conditions"
        );
    }
}