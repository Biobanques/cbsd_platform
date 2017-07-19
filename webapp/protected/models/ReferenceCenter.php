<?php

/**
 * Object to store basic user
 * @author nmalservet
 *
 */
class ReferenceCenter extends LoggableActiveRecord
{
    public $center;

    // This has to be defined in every model, this is same as with standard Yii ActiveRecord
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    // This method is required!
    public function getCollectionName()
    {
        return 'referenceCenter';
    }

    public function rules()
    {
        $result = array(
            array('center', 'required'),
            array('center', 'safe', 'on' => 'search')
        );
        return $result;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'center' => Yii::t('common', 'referenceCentre')
        );
    }

    public function search($caseSensitive = false)
    {
        $criteria = new EMongoCriteria;
        if (isset($this->center) && !empty($this->center)) {
            $regex = '/';
            foreach ($this->center as $value) {
                $regex .= $value;
                if ($value != end($this->center)) {
                    $regex.= '|';
                }
            }
            $regex .= '/i';
            $criteria->addCond('center', '==', new MongoRegex($regex));
        }
        Yii::app()->session['criteria'] = $criteria;
        return new EMongoDocumentDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'center ASC',
            )
        ));
    }
}