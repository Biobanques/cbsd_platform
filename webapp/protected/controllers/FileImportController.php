<?php

class FileImportController extends Controller {

    /**
     * NB : boostrap theme need this column2 layout
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/menu_administration';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    public function accessRules() {
        return array(
            array(
                'allow',
                'actions' => array(
                    'admin',
                    'create',
                    'createPrvmt',
                    'update',
                    'delete',
                    'formatColumn',
                    'exportNonImported',
                    'adminDoublon'
                ),
                'expression' => '$user->getActiveProfil() == "Administrateur"'
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionAdmin() {
        $model = new FileImport('search');
        $fileImport = new FileImport;
        $uploadedFile = new UploadedFile;
        $model->unsetAttributes();
        if (isset($_GET['FileImport'])) {
            $model->setAttributes($_GET['FileImport']);
        }
        if (isset($_POST['rechercher'])) {
            if (isset($_POST['FileImport_id'])) {
                foreach ($_POST['FileImport_id'] as $key => $value) {
                    $this->loadModelFileImport($value)->delete();
                    Yii::app()->user->setFlash('succès', Yii::t('common', 'importedFilesDeleted'));
                }
            } else {
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'importedFilesNotDeleted'));
            }
        }
        if (isset($_POST['UploadedFile'])) {
            $date = date('Ymd_H') . 'h' . date('i');
            $uploadedFile->attributes = $_POST['UploadedFile'];
            $uploadedFile->filename = CUploadedFile::getInstance($uploadedFile, 'filename');
            if ($uploadedFile->filename->getExtensionName() == "csv") {
                $folderNominatif = CommonProperties::$IMPORT_FOLDER_NEUROPATH;
                if (substr($folderNominatif, -1) != '/') {
                    $folderNominatif .= '/';
                }
                if ($_POST['filetype'] == "Donneur") {
                    chdir(Yii::app()->basePath . "/" . $folderNominatif . "/adet/");
                    if ($uploadedFile->validate()) {
                        $uploadedFile->filename->saveAs($uploadedFile->filename->getName());
                        chmod($uploadedFile->filename->getName(), 0777);
                        $file = "not_imported/" . $uploadedFile->filename->getName();
                        $this->importNeuropathNominatif($uploadedFile);
                        $this->deleteUnvalidNeuropath();
                        $answer = Answer::model()->find();
                        if ($answer == null) {
                            $this->createFicheNeuropath();
                        } else {
                            $this->createFicheNeuropathBis();
                        }
                        $fileImport->user = Yii::app()->user->id;
                        $fileImport->filename = $date . '_' . $uploadedFile->filename->getName();
                        $fileImport->filesize = $uploadedFile->filename->getSize();
                        $fileImport->extension = $uploadedFile->filename->getExtensionName();
                        $fileImport->date_import = DateTime::createFromFormat(CommonTools::FRENCH_SHORT_DATE_FORMAT, date(CommonTools::FRENCH_SHORT_DATE_FORMAT));
                        $fileImport->imported = 0;
                        $fileImport->not_imported = 0;
                        $fileImport->save();
                        Yii::app()->user->setFlash('succès', Yii::t('common', 'fileMakerImported'));
                    } else {
                        Yii::app()->user->setFlash('erreur', Yii::t('common', 'fileMakerNotImported'));
                    }
                } elseif ($_POST['filetype'] == "Tranche") {
                    chdir(Yii::app()->basePath . "/" . $folderNominatif . "/tranche/");
                    if ($uploadedFile->validate()) {
                        $uploadedFile->filename->saveAs($uploadedFile->filename->getName());
                        chmod($uploadedFile->filename->getName(), 0777);
                        $file = "not_imported/" . $uploadedFile->filename->getName();
                        if (Tranche::model()->findAll() == null) {
                            $this->importNeuropathTranche($uploadedFile, true);
                        } else {
                            $this->importNeuropathTranche($uploadedFile, false);
                        }
                        $this->deleteDoublonsNeuropath();
                        $this->deleteTrancheWithoutNeuropath();
                        $fileImport->user = Yii::app()->user->id;
                        $fileImport->filename = $date . '_' . $uploadedFile->filename->getName();
                        $fileImport->filesize = $uploadedFile->filename->getSize();
                        $fileImport->extension = $uploadedFile->filename->getExtensionName();
                        $fileImport->date_import = DateTime::createFromFormat(CommonTools::FRENCH_SHORT_DATE_FORMAT, date(CommonTools::FRENCH_SHORT_DATE_FORMAT));
                        $fileImport->imported = 0;
                        $fileImport->not_imported = 0;
                        $fileImport->save();
                        Yii::app()->user->setFlash('succès', Yii::t('common', 'fileMakerImported'));
                    } else {
                        Yii::app()->user->setFlash('erreur', Yii::t('common', 'fileMakerNotImported'));
                    }
                }
            }
        }
        $this->render('admin', array(
            'model' => $model,
            'uploadedFile' => $uploadedFile
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new ColumnFileMaker;
        if (isset($_POST['ColumnFileMaker'])) {
            $model->attributes = $_POST['ColumnFileMaker'];
            if ($model->save()) {
                Yii::app()->user->setFlash('succès', Yii::t('common', 'columnCreated'));
                $this->redirect(array('fileImport/formatColumn'));
            } else {
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'missingFields'));
            }
        }
        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = ColumnFileMaker::model()->findByPk(new MongoId($id));
        if (isset($_POST['ColumnFileMaker'])) {
            $model->attributes = $_POST['ColumnFileMaker'];
            if ($model->save()) {
                Yii::app()->user->setFlash('succès', Yii::t('common', 'columnUpdated'));
                $this->redirect(array('fileImport/formatColumn'));
            }
        }
        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $model = ColumnFileMaker::model()->findByPk(new MongoId($id));
        try {
            $model->delete();
            if (!isset($_GET['ajax'])) {
                Yii::app()->user->setFlash('succès', Yii::t('common', 'columnDeleted'));
            } else {
                echo "<div class='flash-success'>" . Yii::t('common', 'columnDeleted') . "</div>"; //for ajax
            }
        } catch (CDbException $e) {
            if (!isset($_GET['ajax'])) {
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'columnNotDeleted'));
            } else {
                echo "<div class='flash-error'>" . Yii::t('common', 'columnNotDeleted') . "</div>";
            } //for ajax
        }
    }

    public function actionFormatColumn() {
        $modelColumn = new ColumnFileMaker('search');
        $modelColumn->unsetAttributes();
        if (isset($_GET['ColumnFileMaker'])) {
            $modelColumn->setAttributes($_GET['ColumnFileMaker']);
        }
        if (isset($_POST['rechercher'])) {
            if (isset($_POST['ColumnFileMaker_id'])) {
                foreach ($_POST['ColumnFileMaker_id'] as $key => $value) {
                    $this->loadModel($value)->delete();
                    Yii::app()->user->setFlash('succès', Yii::t('common', 'columnFileMakersDeleted'));
                }
            } else {
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'columnFileMakersNotDeleted'));
            }
        }
        $this->render('formatColumn', array(
            'modelColumn' => $modelColumn
        ));
    }

    public function loadModelFileImport($id) {
        $model = FileImport::model()->findByPk(new MongoId($id));
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    public function loadModel($id) {
        $model = columnFileMaker::model()->findByPk(new MongoId($id));
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    public function dropNeuropathCollection() {
        return Neuropath::model()->deleteAll();
    }

    public function importNeuropathNominatif($uploadedFile) {
        $rows = array_map('str_getcsv', file($uploadedFile->filename));
        $header = array_shift($rows);
        $csv = array();
        $neuroExist = false;
        $neuro = Neuropath::model()->findAll();
        if ($neuro != null) {
            $neuroExist = true;
        }
        foreach ($rows as $row) {
            $csv[] = array_combine($header, $row);
        }
        foreach ($csv as $kCSV => $vCSV) {
            $attributes = array();
            if (!$neuroExist) {
                $neuropath = new Neuropath;
            } else {
                $neuropath = new NeuropathBis;
            }
            $patient = $this->initializePatientObject();
            foreach ($vCSV as $cle => $valeur) {
                switch ($cle) {
                    case "Nom naissance":
                        $patient->birthName = str_replace("'", " ", CommonTools::setValue($valeur));
                        break;
                    case "Nom usuel":
                        $patient->useName = str_replace("'", " ", CommonTools::setValue($valeur));
                        break;
                    case "Prénoms":
                        $pos = strpos(CommonTools::setValue($valeur), ",");
                        if ($pos) {
                            $patient->firstName = str_replace("'", " ", substr(CommonTools::setValue($valeur), 0, $pos));
                        } else {
                            $patient->firstName = str_replace("'", " ", CommonTools::setValue($valeur));
                        }
                        break;
                    case "patient_birth_date":
                        $patient->birthDate = CommonTools::setValue($valeur);
                        break;
                    case "Sexe":
                        $patient->sex = CommonTools::setValue($valeur);
                        if ($patient->sex == null) {
                            $patient->sex = "U";
                        }
                        if ($patient->sex == "M" && $patient->birthName == null && $patient->useName != null) {
                            $patient->birthName = $patient->useName;
                        }
                        break;
                    default:
                        if (!in_array($cle, $attributes) && $cle != null && $cle != "") {
                            $attributes[$cle] = CommonTools::setValue($valeur);
                        }
                }
            }
            if ($this->emptyFieldExist($patient) != true) {
                $patientest = CommonTools::wsGetPatient($patient);
                if (!$neuroExist) {
                    $this->addNeuropath($patient, $attributes, $patientest, $neuropath);
                } else {
                    $this->addNeuropathBis($patient, $attributes, $patientest, $neuropath);
                }
            }
        }
    }

    public function addNeuropath($patient, $res, $patientest, $neuropath) {
        if ($patientest === 'NoPatient') {
            $patient = CommonTools::wsAddPatient($patient);
            $patientSIP = get_object_vars($patient);
            foreach ($patientSIP as $kSIP => $vSIP) {
                if ($kSIP == "id" && $vSIP != "") {
                    $neuropath->initSoftAttribute("id_cbsd");
                    $neuropath->id_cbsd = $vSIP;
                    $birthName = "Nom naissance";
                    $useName = "Nom usuel";
                    $firstName = "Prénoms";
                    $birthDate = "Date naissance";
                    $sexe = "Sexe";
                    $neuropath->initSoftAttribute($birthName);
                    $neuropath->$birthName = $patient->birthName;
                    $neuropath->initSoftAttribute($useName);
                    $neuropath->$useName = $patient->useName;
                    $neuropath->initSoftAttribute($firstName);
                    $neuropath->$firstName = $patient->firstName;
                    $neuropath->initSoftAttribute($birthDate);
                    $neuropath->$birthDate = date(CommonTools::FRENCH_SHORT_DATE_FORMAT, strtotime($patient->birthDate));
                    $neuropath->initSoftAttribute($sexe);
                    $neuropath->$sexe = $patient->sex;
                    foreach ($res as $keyAttr => $valueAttr) {
                        if ($keyAttr != null || $keyAttr != "") {
                            $keyAttrTrim = preg_replace("# +#", " ", trim($keyAttr));
                            $columnFileMaker = ColumnFileMaker::model()->findByAttributes(array('currentColumn' => $keyAttrTrim));
                            if ($columnFileMaker != null) {
                                $fileMaker = $columnFileMaker->newColumn;
                                $neuropath->initSoftAttribute($fileMaker);
                                if ($fileMaker != 'id_donor') {
                                    $pos = strpos((string) $valueAttr, "-");
                                    if ($valueAttr == "") {
                                        $neuropath->$fileMaker = null;
                                    } elseif (($pos && $keyAttrTrim == "PrelevementTissus::Braak_Tau") || ($pos && $keyAttrTrim == "PrelevementTissus::Thal_amiloide")) {
                                        $neuropath->$fileMaker = $this->convertNumeric(substr($valueAttr, 0, $pos));
                                    } else {
                                        $neuropath->$fileMaker = $this->convertNumeric($valueAttr);
                                    }
                                } else {
                                    $neuropath->$fileMaker = $valueAttr;
                                }
                            } else {
                                $pos = strpos((string) $valueAttr, "-");
                                $neuropath->initSoftAttribute($keyAttrTrim);
                                if ($valueAttr == "") {
                                    $neuropath->$keyAttrTrim = null;
                                } elseif (($pos && $keyAttrTrim == "PrelevementTissus::Braak_Tau") || ($pos && $keyAttrTrim == "PrelevementTissus::Thal_amiloide")) {
                                    $neuropath->$keyAttrTrim = $this->convertNumeric(substr($valueAttr, 0, $pos));
                                } else {
                                    $neuropath->$keyAttrTrim = $this->convertNumeric($valueAttr);
                                }
                            }
                        }
                    }
                    $neuropath->save();
                }
            }
        }
    }

    public function addNeuropathBis($patient, $res, $patientest, $neuropath) {
        if ($patientest === 'NoPatient') {
            $patient = CommonTools::wsAddPatient($patient);
        } else {
            $patient = $patientest;
        }
        $patientSIP = get_object_vars($patient);
        foreach ($patientSIP as $kSIP => $vSIP) {
            if ($kSIP == "id" && $vSIP != "") {
                $neuropath->initSoftAttribute("id_cbsd");
                $neuropath->id_cbsd = $vSIP;
                $birthName = "Nom naissance";
                $useName = "Nom usuel";
                $firstName = "Prénoms";
                $birthDate = "Date naissance";
                $sexe = "Sexe";
                $neuropath->initSoftAttribute($birthName);
                $neuropath->$birthName = $patient->birthName;
                $neuropath->initSoftAttribute($useName);
                $neuropath->$useName = $patient->useName;
                $neuropath->initSoftAttribute($firstName);
                $neuropath->$firstName = $patient->firstName;
                $neuropath->initSoftAttribute($birthDate);
                if ($patientest == 'NoPatient') {
                    $neuropath->$birthDate = date(CommonTools::FRENCH_SHORT_DATE_FORMAT, strtotime($patient->birthDate));
                } else {
                    $neuropath->$birthDate = $patient->birthDate;
                }
                $neuropath->initSoftAttribute($sexe);
                $neuropath->$sexe = $patient->sex;
                foreach ($res as $keyAttr => $valueAttr) {
                    if ($keyAttr != null || $keyAttr != "") {
                        $keyAttrTrim = preg_replace("# +#", " ", trim($keyAttr));
                        $columnFileMaker = ColumnFileMaker::model()->findByAttributes(array('currentColumn' => $keyAttrTrim));
                        if ($columnFileMaker != null) {
                            $fileMaker = $columnFileMaker->newColumn;
                            $neuropath->initSoftAttribute($fileMaker);
                            if ($fileMaker != "id_donor") {
                                $pos = strpos((string) $valueAttr, "-");
                                if ($valueAttr == "") {
                                    $neuropath->$fileMaker = null;
                                } elseif (($pos && $keyAttrTrim == "PrelevementTissus::Braak_Tau") || ($pos && $keyAttrTrim == "PrelevementTissus::Thal_amiloide")) {
                                    $neuropath->$fileMaker = $this->convertNumeric(substr($valueAttr, 0, $pos));
                                } else {
                                    $neuropath->$fileMaker = $this->convertNumeric($valueAttr);
                                }
                            } else {
                                $neuropath->$fileMaker = (string) $valueAttr;
                            }
                        } else {
                            $pos = strpos((string) $valueAttr, "-");
                            $neuropath->initSoftAttribute($keyAttrTrim);
                            if ($valueAttr == "") {
                                $neuropath->$keyAttrTrim = null;
                            } elseif (($pos && $keyAttrTrim == "PrelevementTissus::Braak_Tau") || ($pos && $keyAttrTrim == "PrelevementTissus::Thal_amiloide")) {
                                $neuropath->$keyAttrTrim = $this->convertNumeric(substr($valueAttr, 0, $pos));
                            } else {
                                $neuropath->$keyAttrTrim = $this->convertNumeric($valueAttr);
                            }
                        }
                    }
                }
                $criteria = new EMongoCriteria;
                $criteria->id_cbsd = $neuropath->id_cbsd;
                $neuropathBis = NeuropathBis::model()->findAll($criteria);
                if ($neuropathBis == null) {
                    $neuropath->save();
                }
            }
        }
    }

    public function importNeuropathTranche($uploadedFile, $first) {
        $rows = array_map('str_getcsv', file($uploadedFile->filename));
        $header = array_shift($rows);
        $csv = array();
        $neuro = null;
        foreach ($rows as $row) {
            $csv[] = array_combine($header, $row);
        }
        foreach ($csv as $kCSV => $vCSV) {
            if ($first) {
                $tranche = new Tranche;
            } else {
                $tranche = new TrancheBis;
            }
            $answer = null;
            foreach ($vCSV as $cle => $valeur) {
                switch ($cle) {
                    case "ID":
                        $tranche->id = (int) $valeur;
                        break;
                    case "PrelevementTissus::id_donor":
                        $tranche->id_donor = $valeur;
                        $neuro = Neuropath::model()->findByAttributes(array("id_donor" => $valeur));
                        if ($neuro != null) {
                            $answer = Answer::model()->findByAttributes(array("id_patient" => (string) $neuro->id_cbsd));
                        }
                        break;
                    case "_PresenceCession":
                        $tranche->presenceCession = $valeur;
                        break;
                    case "Hémishpère":
                        $tranche->hemisphere = $valeur;
                        break;
                    case "idPrelevement":
                        $tranche->idPrelevement = (int) $valeur;
                        break;
                    case "Name_Samples_Tissue":
                        $tranche->nameSamplesTissue = $valeur;
                        break;
                    case "Origin_Samples_Tissue":
                        $tranche->originSamplesTissue = $valeur;
                        break;
                    case "Prélevée":
                        $tranche->prelevee = $valeur;
                        break;
                    case "Prélèvement tissus_Numéro anonymat":
                        $tranche->nAnonymat = (int) $valeur;
                        break;
                    case "Qualité":
                        $tranche->qualite = $valeur;
                        break;
                    case "quantity_available":
                        $tranche->quantityAvailable = $valeur;
                        break;
                    case "Remarques":
                        $tranche->remarques = $valeur;
                        break;
                    case "Selection":
                        $tranche->selection = $valeur;
                        break;
                    case "Sélectionnée":
                        $tranche->selectionnee = $valeur;
                        break;
                    case "storage_conditions":
                        $tranche->storageConditions = $valeur;
                        break;
                    default:
                }
            }
            if (isset($tranche) && $tranche != null && $tranche->id_donor != null) {
                $tranche->save();
            }
        }
    }

    public function deleteDoublonsNeuropath() {
        $neuropathBis = NeuropathBis::model()->findAll();
        if ($neuropathBis != null) {
            foreach ($neuropathBis as $neuro) {
                $diff = false;
                $criteria = new EMongoCriteria;
                $criteria->id_cbsd = $neuro->id_cbsd;
                $id_cbsd = Neuropath::model()->find($criteria);
                $idAnswerBis = AnswerBis::model()->findByAttributes(array('id_patient' => (string) $neuro->id_cbsd));
                if ($id_cbsd != null) {
                    foreach ($id_cbsd as $k => $v) {
                        if ($k != null && $v != null && $k != "_id") {
                            if (isset($id_cbsd->$k) && isset($neuro->$k)) {
                                if ($id_cbsd->$k != $neuro->$k) {
                                    $diff = true;
                                }
                            } else {
                                $diff = true;
                            }
                        }
                    }
                    if (!$diff) {
                        $criteriaBis = new EMongoCriteria;
                        $criteriaBis->id_donor = $id_cbsd->id_donor;
                        $id_trancheBis = TrancheBis::model()->findAll($criteriaBis);
                        if ($this->existDiffTranche($id_trancheBis) == false) {
                            $neuro->delete();
                            $idAnswerBis->delete();
                            foreach ($id_trancheBis as $idTrancheBis) {
                                $idTrancheBis->delete();
                            }
                        }
                    }
                }
            }
        }
    }

    public function existDiffTranche($id_trancheBis) {
        $diff = false;
        if ($id_trancheBis != null) {
            foreach ($id_trancheBis as $tranc) {
                $criteria = new EMongoCriteria;
                $criteria->id = $tranc->id;
                $criteria->idPrelevement = $tranc->idPrelevement;
                $id_tranche = Tranche::model()->find($criteria);
                if ($id_tranche != null) {
                    foreach ($id_tranche as $k => $v) {
                        if ($k != null && $v != null && $k != "_id") {
                            if (isset($id_tranche->$k) && isset($tranc->$k)) {
                                if ($id_tranche->$k != $tranc->$k) {
                                    $diff = true;
                                }
                            } else {
                                $diff = true;
                            }
                        }
                    }
                }
            }
        }
        return $diff;
    }

    public function deleteTrancheWithoutNeuropath() {
        $id_trancheBis = TrancheBis::model()->findAll();
        foreach ($id_trancheBis as $idTranc) {
            $criteria = new EMongoCriteria;
            $criteria->id_donor = $idTranc->id_donor;
            $neuropath = Neuropath::model()->find($criteria);
            if ($neuropath == null) {
                $idTranc->delete();
            }
        }
    }

    public function getPatientSIP($patient) {
        foreach ($patient as $kSIP => $vSIP) {
            if ($kSIP == "id" && $vSIP != "") {
                return $vSIP;
            } else {
                return null;
            }
        }
    }

    public function initializePatientObject() {
        $patient = (object) null;
        $patient->id = null;
        $patient->source = 1; // Banque de cerveaux
        $patient->sourceId = null;
        $patient->birthName = null;
        $patient->useName = null;
        $patient->firstName = null;
        $patient->birthDate = null;
        $patient->sex = null;
        return $patient;
    }

    /*
     * Vérifie si un des champs "birthName", "useName", "firstName", "birthDate", "gender" est vide
     */

    public function emptyFieldExist($patient) {
        foreach ($patient as $field => $value) {
            if ($field != "id" && $field != "sourceId" && empty($value)) {
                return true;
            }
        }
    }

    /*
     * Ecrit dans un fichier les patients qui n'ont pas pu être importé ("A SIP item is missing in the file")
     */

    public function writePatientsNotImported($patient, $file) {
        file_put_contents($file, print_r($patient, true), FILE_APPEND);
    }

    public function deleteUnvalidNeuropath() {
        $neuropath = Neuropath::model()->findAll();
        foreach ($neuropath as $neuro) {
            if (!isset($neuro->id_cbsd)) {
                $neuro->delete();
            }
        }
    }

    public function deleteImportNeuropathForms() {
        $criteria = new EMongoCriteria;
        $criteria->id = 'neuropath_filemaker_form';
        $neuropath = Answer::model()->findAll($criteria);
        if ($neuropath != null) {
            foreach ($neuropath as $neuro) {
                $neuro->delete();
            }
        }
    }

    public function createFicheNeuropath() {
        $criteria = new EMongoCriteria;
        $criteria->login = "Bernard";
        $neuropath = Neuropath::model()->findAll();
        if ($neuropath != null) {
            foreach ($neuropath as $neuro) {
                $this->updateFicheNeuropath($neuro);
            }
        }
    }

    public function createFicheByNeuropathId($newNeuropath) {
        $criteria = new EMongoCriteria;
        $criteria->id_patient = (string) $newNeuropath->id_cbsd;
        $answer = Answer::model()->find($criteria);
        AnswerBis::model()->find($criteria)->delete();
        if ($answer != null) {
            $answer->delete();
        }
        if ($newNeuropath != null) {
            $this->updateFicheNeuropath($newNeuropath);
        }
        Yii::app()->user->setFlash('succès', 'La fiche a bien été mise à jour.');
    }

    public function createFicheNeuropathBis() {
        $type = "";
        $criteria = new EMongoCriteria;
        $criteria->login = "Bernard";
        $user = User::model()->find($criteria);
        $neuropath = NeuropathBis::model()->findAll();
        if ($neuropath != null) {
            foreach ($neuropath as $neuro) {
                $answer = new AnswerBis;
                $answer->creator = "Bernard TE";
                $answer->id = "neuropath_filemaker_form";
                $answer->type = "neuropathologique";
                $answer->login = new MongoId("561b82c3cfa2418dd83529cb");
                $answer->questionnaireMongoId = new MongoId();
                $answer->name = "Import Neuropath";
                $answer->last_modified = DateTime::createFromFormat(CommonTools::FRENCH_SHORT_DATE_FORMAT, date(CommonTools::FRENCH_SHORT_DATE_FORMAT));
                $answer->description = "Données neuropathologiques de la base FileMaker";
                $answer->last_updated = DateTime::createFromFormat(CommonTools::FRENCH_SHORT_DATE_FORMAT, date(CommonTools::FRENCH_SHORT_DATE_FORMAT));
                $answerGroup = new AnswerGroup;
                $answerGroup->id = "onglet";
                $answerGroup->title = "Données neuropathologiques FileMaker";
                $answerGroup->title_fr = $answerGroup->title;
                foreach ($neuro as $k => $v) {
                    if ($k != "_id" && $k != "Nom naissance" && $k != "Nom usuel" && $k != "Prénoms" && $k != "Date naissance" && $k != "Sexe") {
                        if ($k == "id_cbsd") {
                            $answer->id_patient = (string) $v;
                        } else {
                            $answerQuestion = new AnswerQuestion;
                            $answerQuestion->id = (string) $k;
                            $answerQuestion->label = (string) $k;
                            $answerQuestion->label_fr = (string) $k;
                            $type = ColumnFileMaker::model()->findByAttributes(array('newColumn' => $answerQuestion->label));
                            if ($type != null) {
                                $answerQuestion->type = ColumnFileMaker::model()->findByAttributes(array('newColumn' => $answerQuestion->label))->type;
                            } else {
                                $answerQuestion->type = "input";
                            }
                            $answerQuestion->style = "";
                            if ($answerQuestion->type == "date") {
                                if ($v == "") {
                                    $answerQuestion->answer = null;
                                } else {
                                    $answerQuestion->answer = DateTime::createFromFormat(CommonTools::FRENCH_SHORT_DATE_FORMAT, date(CommonTools::FRENCH_SHORT_DATE_FORMAT, strtotime($v)));
                                }
                            } elseif ($answerQuestion->type == "number") {
                                if ($v != null) {
                                    $answerQuestion->answer = new MongoInt32($v);
                                } else {
                                    $answerQuestion->answer = null;
                                }
                            } elseif ($answerQuestion->type == "radio") {
                                if ($type != null) {
                                    $answerQuestion->values = ColumnFileMaker::model()->findByAttributes(array('newColumn' => $answerQuestion->label))->values;
                                }
                                $answerQuestion->answer = $v;
                            } else {
                                $answerQuestion->answer = $v;
                            }
                            $answerGroup->answers[] = $answerQuestion;
                        }
                    }
                }
                $answer->answers_group[] = $answerGroup;
                $answer->available = 0;
                $answer->save();
            }
        }
    }

    public function updateFicheNeuropath($neuro) {
        $answer = new Answer;
        $answer->creator = "Bernard TE";
        $answer->id = "neuropath_filemaker_form";
        $answer->type = "neuropathologique";
        $answer->login = new MongoId("561b82c3cfa2418dd83529cb");
        $answer->questionnaireMongoId = new MongoId();
        $answer->name = "Import Neuropath";
        $answer->last_modified = DateTime::createFromFormat(CommonTools::FRENCH_SHORT_DATE_FORMAT, date(CommonTools::FRENCH_SHORT_DATE_FORMAT));
        $answer->description = "Données neuropathologiques de la base FileMaker";
        $answer->last_updated = DateTime::createFromFormat(CommonTools::FRENCH_SHORT_DATE_FORMAT, date(CommonTools::FRENCH_SHORT_DATE_FORMAT));
        $answerGroup = new AnswerGroup;
        $answerGroup->id = "onglet";
        $answerGroup->title = "Données neuropathologiques FileMaker";
        $answerGroup->title_fr = $answerGroup->title;
        $idDonor = 0;
        foreach ($neuro as $k => $v) {
            if ($k != "_id" && $k != "Nom naissance" && $k != "Nom usuel" && $k != "Prénoms" && $k != "Date naissance" && $k != "Sexe") {
                if ($k == "id_cbsd") {
                    $answer->id_patient = (string) $v;
                } else {
                    if ($k == "id_donor") {
                        $idDonor = $v;
                    }
                    $answerQuestion = new AnswerQuestion;
                    $answerQuestion->id = (string) $k;
                    $answerQuestion->label = (string) $k;
                    $answerQuestion->label_fr = (string) $k;
                    $type = ColumnFileMaker::model()->findByAttributes(array('newColumn' => $answerQuestion->label));
                    if ($type != null) {
                        $answerQuestion->type = ColumnFileMaker::model()->findByAttributes(array('newColumn' => $answerQuestion->label))->type;
                    } else {
                        $answerQuestion->type = "input";
                    }
                    $answerQuestion->style = "";
                    if ($answerQuestion->type == "date") {
                        if ($v == "") {
                            $answerQuestion->answer = null;
                        } else {
                            $answerQuestion->answer = DateTime::createFromFormat(CommonTools::FRENCH_SHORT_DATE_FORMAT, date(CommonTools::FRENCH_SHORT_DATE_FORMAT, strtotime($v)));
                        }
                    } elseif ($answerQuestion->type == "number") {
                        if ($v != null) {
                            $answerQuestion->answer = new MongoInt32($v);
                        } else {
                            $answerQuestion->answer = null;
                        }
                    } elseif ($answerQuestion->type == "radio") {
                        if ($type != null) {
                            $answerQuestion->values = ColumnFileMaker::model()->findByAttributes(array('newColumn' => $answerQuestion->label))->values;
                        }
                        $answerQuestion->answer = $v;
                    } else {
                        $answerQuestion->answer = $v;
                    }
                    $answerGroup->answers[] = $answerQuestion;
                }
            }
        }
        $answer->answers_group[] = $answerGroup;
        $answer->idDonor = $idDonor;
        $answer->save();
    }

    public function actionAdminDoublon() {
        $modelAnswerBis = NeuropathBis::model()->find();
        if ($modelAnswerBis != null) {
            $modelAnswer = Neuropath::model()->findByAttributes(array('id_cbsd' => $modelAnswerBis->id_cbsd));
            if (isset($_GET['acceptAll'])) {
                $newNeuropath = $this->copyNeuropathBisToNeuropath($modelAnswerBis->id_cbsd);
                $this->copyTrancheBisToTranche($modelAnswerBis->id_donor);
                $this->createFicheByNeuropathId($newNeuropath);
                Yii::app()->user->setFlash('succès', 'La fiche a bien été accepté.');
                $this->redirect(array('fileImport/adminDoublon'));
            } elseif (isset($_GET['refuseAll'])) {
                $tranche = TrancheBis::model()->findAllByAttributes(array('id_donor' => $modelAnswerBis->id_donor));
                foreach ($tranche as $tranc) {
                    $tranc->delete();
                }
                $modelAnswerBis->delete();
                $modelBis = AnswerBis::model()->findByAttributes(array('id_patient' => (string) $modelAnswerBis->id_cbsd));
                $modelBis->delete();
                Yii::app()->user->setFlash('succès', 'La fiche a bien été refusé.');
                $this->redirect(array('fileImport/adminDoublon'));
            } elseif (isset($_GET['next'])) {
                $modelBisBis = new NeuropathBisBis;
                foreach ($modelAnswerBis as $k => $v) {
                    if (isset($modelAnswerBis->$k)) {
                        $modelBisBis->initSoftAttribute($k);
                        $modelBisBis->$k = $modelAnswerBis->$k;
                    } else {
                        $modelBisBis->initSoftAttribute($k);
                        $modelBisBis->$k = $v;
                    }
                }
                $modelAnswerBis->delete();
                $modelBisBis->save();
                Yii::app()->user->setFlash('succès', 'La fiche a bien été passé en revue.');
                if (NeuropathBis::model()->find() != null) {
                    $this->redirect(array('fileImport/adminDoublon'));
                } else {
                    $this->redirect(array('administration/index'));
                }
            }
        } else {
            $modelAnswer = null;
            $modelAnswerBisBis = NeuropathBisBis::model()->findAll();
            if ($modelAnswerBisBis != null) {
                foreach ($modelAnswerBisBis as $model) {
                    $modelAnswerBisBisToBis = new NeuropathBis;
                    foreach ($model as $k => $v) {
                        $modelAnswerBisBisToBis->initSoftAttribute($k);
                        $modelAnswerBisBisToBis->$k = $model->$k;
                    }
                    $modelAnswerBisBisToBis->save();
                    $model->delete();
                }
                if ($modelAnswerBisBis == null) {
                    Yii::app()->user->setFlash('succès', 'La fiche a bien été passé en revue.');
                    $this->redirect(array('administration/index'));
                } else {
                    $this->redirect(array('fileImport/adminDoublon'));
                }
            }
        }
        $this->render('adminDoublon', array(
            'modelAnswer' => $modelAnswer,
            'modelAnswerBis' => $modelAnswerBis
        ));
    }

    public function copyNeuropathBisToNeuropath($idCbsd) {
        $neuropath = Neuropath::model()->findByAttributes(array('id_cbsd' => $idCbsd));
        $neuropath->delete();
        $neuropathBis = NeuropathBis::model()->findByAttributes(array('id_cbsd' => $idCbsd));
        $newNeuropath = new Neuropath;
        foreach ($neuropathBis as $k => $v) {
            $newNeuropath->initSoftAttribute($k);
            $newNeuropath->$k = $v;
        }
        $newNeuropath->save();
        $neuropathBis->delete();
        return $newNeuropath;
    }

    public function copyTrancheBisToTranche($idDonor) {
        $oldTranche = Tranche::model()->findAllByAttributes(array('id_donor' => $idDonor));
        if ($oldTranche != null) {
            foreach ($oldTranche as $oldTranc) {
                $oldTranc->delete();
            }
        }
        $trancheBis = TrancheBis::model()->findAllByAttributes(array('id_donor' => $idDonor));
        foreach ($trancheBis as $trancBis) {
            $newTranche = new Tranche;
            foreach ($trancBis as $k => $v) {
                if (!isset($trancBis->$k)) {
                    $newTranche->initSoftAttribute($k);
                }
                $newTranche->$k = $v;
            }
            if (isset($newTranche) && $newTranche != null) {
                $newTranche->save();
            }
            $trancBis->delete();
        }
    }

    public function convertNumeric($value) {
        switch ($value) {
            case "I": return 1;
                break;
            case "II": return 2;
                break;
            case "III": return 3;
                break;
            case "IV": return 4;
                break;
            case "V": return 5;
                break;
            case "VI": return 6;
                break;
            default: if (ctype_digit($value))
                    return (int) $value;
                else
                    return trim($value);
        }
    }

    public function actionExportNonImported($id) {
        $fileImport = FileImport::model()->findByPk(new MongoId($id));
        $file = $fileImport->filename;
        $filePath = CommonProperties::$EXPORT_NON_IMPORTED_PATH;
        if (substr($filePath, -1) != '/') {
            $filePath .= '/';
        }
        chdir(Yii::app()->basePath . "/" . $filePath);
        $filename = str_replace('.xml', '.txt', $file);
        if (file_exists($filename)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($filename));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filename));
            ob_clean();
            flush();
            readfile($filename);
        } else {
            Yii::app()->user->setFlash('erreur', 'Le projet n\'a pas été supprimé.');
        }
    }

}
