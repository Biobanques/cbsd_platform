<?php

class Prelevement extends EMongoSoftDocument
{
    // This has to be defined in every model, this is same as with standard Yii ActiveRecord
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    // This method is required!
    public function getCollectionName()
    {
        return 'Prelevement';
    }
    
    public function getAllPrelevements() {
        $res = array();
        $prvmt = Prelevement::model()->findAll();
        foreach ($prvmt as $p) {
            if (!in_array($p->currentColumn, $res)) {
                array_push($res, $p->currentColumn);
            }
        }
        return $res;
    }
}