<?php

class Tranche extends EMongoSoftDocument
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
        return 'Tranche';
    }
    
    /**
     * @return array validation rules for model attributes.
     */
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

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'Identifiant du prélèvement',
            'id_donor' => "Identifiant du donneur",
            'originSamplesTissue' => "Origin Samples Tissue",
            'quantityAvailable' => "Quantity available",
            'storageConditions' => "Storage conditions",
            'presenceCession' => "Présence cession",
            'hemisphere' => "Hémisphère",
            'idPrelevement' => "Identifiant du prélèvement",
            'nameSamplesTissue' => "Nom de l'échantillon",
            'prelevee' => "Prélevée",
            'nAnonymat' => "N° anonymat",
            'qualite' => "Qualité"
        );
    }
    
    public function setQuantityAvailable() {
        return array(
            'Available' => 'Available',
            'Not available' => 'Not available'
        );
    }
}