<?php

/**
 * Object answer to store a questionnaire definition + answers
 * Copy of object questionnaire to prevent problems of update with questionnaire and forwar compatibility
 * @property integer $id
 * @author Bernard TE
 *
 */
class FileImport extends LoggableActiveRecord {

    /**
     *
     */
// This has to be defined in every model, this is same as with standard Yii ActiveRecord
    public $user;
    public $filename;
    public $filesize;
    public $extension;
    public $date_import;
    public $imported;
    public $not_imported;

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
        return 'FileImport';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            // The following rule is used by search().
            array('user, filename, filesize, extension, date_import, imported, not_imported', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'user' => Yii::t('common','login'),
            'filename' => Yii::t('common','filename'),
            'filesize' => Yii::t('common','filesize'),
            'extension' => 'Extension',
            'date_import' => Yii::t('common','dateImport'),
            'imported' => Yii::t('common','imported'),
            'not_imported' => Yii::t('common','notImported')
        );
    }
   
    public function search($caseSensitive = false) {
        $criteria = new EMongoCriteria;
        if (isset($this->type) && !empty($this->type)) {
            $criteria->addCond('type', '==', new MongoRegex(CommonTools::regexString($this->type)));
        }

        if (isset($this->user) && !empty($this->user)) {
            $regex = CommonTools::regexString($this->user);
            $criteriaUser = new EMongoCriteria;
            $criteriaUser->nom = new MongoRegex($regex);
            $criteriaUser->select(array('_id'));
            $users = User::model()->findAll($criteriaUser);
            $listUsers = array();
            if ($users != null) {
                foreach ($users as $user) {
                    $listUsers[] = $user->_id;
                }
            }
            $criteria->addCond('user', 'in', $listUsers);
        }

        if (isset($this->filename) && !empty($this->filename)) {
            $criteria->addCond('filename', '==', new MongoRegex(CommonTools::regexString($this->filename)));
        }

        if (isset($this->filesize) && !empty($this->filesize)) {
            $criteria->addCond('filesize', '==', new MongoRegex(CommonTools::regexNumeric($this->filesize)));
        }

        if (isset($this->date_import) && !empty($this->date_import)) {
            $answerFormat = CommonTools::formatDatePicker($this->date_import);
            $date_from = str_replace('/', '-', $answerFormat['date_from']);
            $date_to = str_replace('/', '-', $answerFormat['date_to']);
            $criteria->date_import->date = array('$gte' => date('Y-m-d', strtotime($date_from)) . " 00:00:00.000000", '$lte' => date('Y-m-d', strtotime($date_to)) . " 23:59:59.000000");
        }
       
        $criteria->sort('date_import', EMongoCriteria::SORT_DESC);
        Yii::app()->session['criteria'] = $criteria;
        return new EMongoDocumentDataProvider($this, array(
            'criteria' => $criteria
        ));
    }
   
    /**
     * get the last updatedvalue into a french date format JJ/MM/AAAA
     * @return type
     */
    public function getDateImport() {
        if ($this->date_import != null) {
            return date('d/m/Y H:i', strtotime($this->date_import['date']));
        } else {
            return null;
        }
    }
   
    public function getNonImportedNumber() {
        if ($this->not_imported != null) {
            return $this->not_imported;
        } else {
            return null;
        }
    }

}