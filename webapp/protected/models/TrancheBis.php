<?php

class TrancheBis extends Tranche
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
}