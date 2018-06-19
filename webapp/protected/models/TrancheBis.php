<?php

class TrancheBis extends EMongoSoftDocument
{
    public $id;
    public $id_donor;
    public $presenceCession;
    public $hemisphere;
    public $idPrelevement;
    public $nameSamplesTissue;
    public $originSamplesTissue;
    public $prelevee;
    public $nAnonymat;
    public $qualite;
    public $quantityAvailable;
    public $remarques;
    public $selection;
    public $selectionnee;
    public $storageConditions;
    
    // This has to be defined in every model, this is same as with standard Yii ActiveRecord
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    // This method is required!
    public function getCollectionName()
    {
        return 'TrancheBis';
    }
    
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        $result = array(
            array('id_donor', 'required'),
            array(
                'id_donor,presenceCession,hemisphere,idPrelevement,nameSamplesTissue,originSamplesTissue,prelevee,nAnonymat,qualite,quantityAvailable,remarques,selection,selectionnee,storageConditions',
                'safe'
            )
        );
        return $result;
    }
}