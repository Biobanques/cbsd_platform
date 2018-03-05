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
            $folderNominatif = CommonProperties::$IMPORT_FOLDER_NOMINATIF;
            if (substr($folderNominatif, -1) != '/') {
                $folderNominatif .= '/';
            }
            chdir(Yii::app()->basePath . "/" . $folderNominatif);
            if ($uploadedFile->validate()) {
                $uploadedFile->filename->saveAs($uploadedFile->filename->getName());
                chmod($uploadedFile->filename->getName(), 0777);
                $file = "not_imported/" . $uploadedFile->filename->getName();
                $this->importNeuropathNominatif($uploadedFile);
                $this->deleteUnvalidNeuropath();
                $this->deleteDoublonsNeuropath();
                $this->deleteImportNeuropathForms();
                $this->createFicheNeuropath();
                $this->createFicheNeuropathBis();
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

    public function actionCreatePrvmt() {
        $model = new Prelevement;
        if (isset($_POST['Prelevement'])) {
            $model->attributes = $_POST['Prelevement'];
            $model->type = "radio";
            $model->values = "Available,Not available";
            if ($model->save()) {
                Yii::app()->user->setFlash('succès', 'OK');
                $this->redirect(array('fileImport/formatColumn'));
            } else {
                Yii::app()->user->setFlash('erreur', 'KO');
            }
        }
        $this->render('createPrvmt', array(
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
        $prelevement = new Prelevement('search');
        $modelColumn->unsetAttributes();
        $prelevement->unsetAttributes();
        if (isset($_GET['ColumnFileMaker'])) {
            $modelColumn->setAttributes($_GET['ColumnFileMaker']);
        }
        if (isset($_GET['Prelevement'])) {
            $modelColumn->setAttributes($_GET['Prelevement']);
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
            'modelColumn' => $modelColumn,
            'prelevement' => $prelevement
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
        $res = array();
        $rows = array_map('str_getcsv', file($uploadedFile->filename));
        $header = array_shift($rows);
        $csv = array();
        $patientBis = array();
        $tissu = "";
        $qte = "";
        $neuroExist = false;
        $neuro = Neuropath::model()->findAll();
        if ($neuro != null) {
            $neuroExist = true;
        }
        foreach ($rows as $row) {
            $csv[] = array_combine($header, $row);
        }
        foreach ($csv as $kCSV => $vCSV) {
            $prelevements = array();
            $attributes = array();
            $neuropath = new Neuropath;
            $patient = $this->initializePatientObject();
            foreach ($vCSV as $cle => $valeur) {
                switch ($cle) {
                    case "Nom naissance":
                        $patient->birthName = str_replace("'", " ", CommonTools::setValue($valeur));
                        break;
                    case "Nom usuel":
                        $patient->useName = str_replace("'", " ", CommonTools::setValue($valeur));
                        break;
                    case "Prenoms":
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
            $tissu = null;
            $cong = null;
            $qte = null;
            if ($attributes['PrelevementTissusTranche::Origin_Samples_Tissue'] != null || $attributes['PrelevementTissusTranche::Origin_Samples_Tissue'] != "") {
                $prelevements[(string) $attributes['PrelevementTissusTranche::Origin_Samples_Tissue']] = $attributes['PrelevementTissusTranche::quantity_available'];
                $tissu = (string) preg_replace("# +#", " ", trim($attributes['PrelevementTissusTranche::Origin_Samples_Tissue']));
                $cong = $attributes['PrelevementTissusTranche::storage_conditions'];
                $qte = $attributes['PrelevementTissusTranche::quantity_available'];
            }
            unset($attributes['PrelevementTissusTranche::Origin_Samples_Tissue']);
            unset($attributes['PrelevementTissusTranche::quantity_available']);
            unset($attributes['PrelevementTissusTranche::storage_conditions']);
            $res = CommonTools::array_merge_custom($attributes, $prelevements);
            if ($patient->birthName == null && $patient->useName == null && $patient->firstName == null && $patient->birthDate == null && $patient->sex == "U") {
                $patient->birthName = $patientBis['birthName'];
                $patient->useName = $patientBis['useName'];
                $patient->firstName = $patientBis['firstName'];
                $patient->birthDate = $patientBis['birthDate'];
                $patient->sex = $patientBis['sex'];
            } else {
                $patientBis = null;
            }
            if ($this->emptyFieldExist($patient) != true) {
                $patientBis['birthName'] = $patient->birthName;
                $patientBis['useName'] = $patient->useName;
                $patientBis['firstName'] = $patient->firstName;
                $patientBis['birthDate'] = $patient->birthDate;
                $patientBis['sex'] = $patient->sex;
                $patientest = CommonTools::wsGetPatient($patient);
                if (!$neuroExist) {
                    $this->addNeuropathFirstTime($patient, $res, $patientest, $neuropath, $tissu, $qte, $cong);
                } else {
                    $this->addNeuropath($patient, $res, $patientest, $neuropath, $tissu, $qte, $cong);
                }
            }
        }
    }

    public function addNeuropathFirstTime($patient, $res, $patientest, $neuropath, $tissu, $qte, $cong) {
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
                            $prvmt = Prelevement::model()->findByAttributes(array('currentColumn' => $keyAttrTrim));
                            if ($columnFileMaker != null || $prvmt != null) {
                                if ($prvmt != null) {
                                    if ($cong != null) {
                                        $fileMaker = $prvmt->newColumn . "_" . $cong;
                                    } else {
                                        $fileMaker = $prvmt->newColumn;
                                    }
                                } else {
                                    $fileMaker = $columnFileMaker->newColumn;
                                }
                                $neuropath->initSoftAttribute($fileMaker);
                                $pos = strpos((string) $valueAttr, "-");
                                if ($valueAttr == "") {
                                    $neuropath->$fileMaker = null;
                                } elseif (($pos && $keyAttrTrim == "PrelevementTissus::Braak_Tau") || ($pos && $keyAttrTrim == "PrelevementTissus::Thal_amiloide")) {
                                    $neuropath->$fileMaker = $this->convertNumeric(substr($valueAttr, 0, $pos));
                                } else {
                                    $neuropath->$fileMaker = $this->convertNumeric($valueAttr);
                                }
                            } else {
                                $pos = strpos((string) $valueAttr, "-");
                                if ($cong != null) {
                                    $keyAttrTrim = $keyAttrTrim . "_" . $cong;
                                } else {
                                    $keyAttrTrim = $keyAttrTrim;
                                }
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
        } else {
            $patientSIP = get_object_vars($patientest);
            foreach ($patientSIP as $k => $v) {
                if ($k == "id") {
                    $criteria = new EMongoCriteria;
                    $criteria->id_cbsd = $v;
                    $neuropath = Neuropath::model()->find($criteria);
                    if ($neuropath != null && $tissu != null) {
                        foreach ($neuropath as $key => $value) {
                            if ($key !== $tissu) {
                                $prvmt = Prelevement::model()->findByAttributes(array('currentColumn' => $tissu));
                                if ($prvmt != null) {
                                    if ($cong != null) {
                                        $fileMaker = $prvmt->newColumn . "_" . $cong;
                                    } else {
                                        $fileMaker = $prvmt->newColumn;
                                    }
                                    $neuropath->initSoftAttribute($fileMaker);
                                    $neuropath->$fileMaker = $qte;
                                    $neuropath->save();
                                } else {
                                    $neuropath->initSoftAttribute($tissu);
                                    $neuropath->$tissu = $qte;
                                    $neuropath->save();
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function addNeuropath($patient, $res, $patientest, $neuropath, $tissu, $qte, $cong) {
        if ($patientest === 'NoPatient') {
            $patient = CommonTools::wsAddPatient($patient);
            $patientSIP = get_object_vars($patient);
            $id = $this->getPatientSIP($patientSIP);
            $_SESSION['NoPatient'] = $id;
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
                    $neuropath->$birthDate = $patient->birthDate;
                    $neuropath->initSoftAttribute($sexe);
                    $neuropath->$sexe = $patient->sex;
                    foreach ($res as $keyAttr => $valueAttr) {
                        if ($keyAttr != null || $keyAttr != "") {
                            $keyAttrTrim = (string) preg_replace("# +#", " ", trim($keyAttr));
                            $columnFileMaker = ColumnFileMaker::model()->findByAttributes(array('currentColumn' => $keyAttrTrim));
                            $prvmt = Prelevement::model()->findByAttributes(array('currentColumn' => $keyAttrTrim));
                            if ($columnFileMaker != null || $prvmt != null) {
                                if ($prvmt != null) {
                                    if ($cong != null) {
                                        $fileMaker = (string) $prvmt->newColumn . "_" . $cong;
                                    } else {
                                        $fileMaker = (string) $prvmt->newColumn;
                                    }
                                } else {
                                    $fileMaker = $columnFileMaker->newColumn;
                                }
                                $neuropath->initSoftAttribute($fileMaker);
                                $pos = strpos((string) $valueAttr, "-");
                                if ($valueAttr == "") {
                                    $neuropath->$fileMaker = null;
                                } elseif (($pos && $keyAttrTrim == "PrelevementTissus::Braak_Tau") || ($pos && $keyAttrTrim == "PrelevementTissus::Thal_amiloide")) {
                                    $neuropath->$fileMaker = $this->convertNumeric(substr($valueAttr, 0, $pos));
                                } else {
                                    $neuropath->$fileMaker = $this->convertNumeric($valueAttr);
                                }
                            } else {
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
        } else {
            $patientSIP = get_object_vars($patientest);
            $id = $this->getPatientSIP($patientSIP);
            if (isset($_SESSION['NoPatient']) && $_SESSION['NoPatient'] == $id) {
                $criteria = new EMongoCriteria;
                $criteria->id_cbsd = $id;
                $neuropath = Neuropath::model()->find($criteria);
                if ($neuropath != null && $tissu != null && isset($_SESSION['patientId']) && $_SESSION['patientId'] == $id && $_SESSION['NoPatient'] == 1) {
                    foreach ($neuropath as $key => $value) {
                        if ($key !== $tissu) {
                            if ($cong != null) {
                                $tissu = (string) str_replace('.', '', $tissu) . "_" . $cong;
                            } else {
                                $tissu = (string) str_replace('.', '', $tissu);
                            }
                            $neuropath->initSoftAttribute($tissu);
                            $neuropath->$tissu = $qte;
                            $neuropath->save();
                        }
                    }
                }
            } else {
                $criteria = new EMongoCriteria;
                $criteria->id_cbsd = $id;
                $neuropathBis = NeuropathBis::model()->find($criteria);
                if ($neuropathBis === null) {
                    $neuropathBis = new NeuropathBis;
                    $neuropathBis->initSoftAttribute("id_cbsd");
                    foreach ($patientSIP as $k => $v) {
                        if ($k == "id") {
                            $neuropathBis->id_cbsd = $v;
                            $birthName = "Nom naissance";
                            $useName = "Nom usuel";
                            $firstName = "Prénoms";
                            $birthDate = "Date naissance";
                            $sexe = "Sexe";
                            $neuropathBis->initSoftAttribute($birthName);
                            $neuropathBis->$birthName = $patient->birthName;
                            $neuropathBis->initSoftAttribute($useName);
                            $neuropathBis->$useName = $patient->useName;
                            $neuropathBis->initSoftAttribute($firstName);
                            $neuropathBis->$firstName = $patient->firstName;
                            $neuropathBis->initSoftAttribute($birthDate);
                            $neuropathBis->$birthDate = $patient->birthDate;
                            $neuropathBis->initSoftAttribute($sexe);
                            $neuropathBis->$sexe = $patient->sex;
                        }
                    }
                }
                foreach ($res as $keyAttr => $valueAttr) {
                    if ($keyAttr != null || $keyAttr != "") {
                        $keyAttrTrim = (string) preg_replace("# +#", " ", trim($keyAttr));
                        $columnFileMaker = ColumnFileMaker::model()->findByAttributes(array('currentColumn' => (string) $keyAttrTrim));
                        $prvmt = Prelevement::model()->findByAttributes(array('currentColumn' => (string) $keyAttrTrim));
                        if ($columnFileMaker != null || $prvmt != null) {
                            if ($prvmt != null) {
                                if ($cong != null) {
                                    $fileMaker = (string) str_replace('.', '', $prvmt->newColumn) . "_" . $cong;
                                } else {
                                    $fileMaker = (string) str_replace('.', '', $prvmt->newColumn);
                                }
                            } else {
                                $fileMaker = (string) str_replace('.', '', $columnFileMaker->newColumn);
                            }
                            $criteriaNeuro = new EMongoCriteria;
                            $criteriaNeuro->id_cbsd = $id;

                            $neuropathBis->initSoftAttribute((string) $fileMaker);
                            $pos = strpos((string) $valueAttr, "-");
                            if ($valueAttr != "") {
                                if ($fileMaker !== "id_donor") {
                                    if (($pos && $keyAttrTrim == "PrelevementTissus::Braak_Tau") || ($pos && $keyAttrTrim == "PrelevementTissus::Thal_amiloide")) {
                                        $neuropathBis->$fileMaker = $this->convertNumeric(substr($valueAttr, 0, $pos));
                                    } else {
                                        $neuropathBis->$fileMaker = $this->convertNumeric($valueAttr);
                                    }
                                } else {
                                    $neuropathBis->id_donor = $this->convertNumeric($valueAttr);
                                }
                            }
                        } else {
                            $attr = (string) str_replace('.', '', $fileMaker);
                            if (!isset($neuropathBis->$attr)) {
                                $neuropathBis->initSoftAttribute((string) $attr);
                                if ($valueAttr != "") {
                                    if ($fileMaker !== "id_donor") {
                                        if (($pos && $attr == "PrelevementTissus::Braak_Tau") || ($pos && $attr == "PrelevementTissus::Thal_amiloide")) {
                                            $neuropathBis->$attr = $this->convertNumeric(substr($valueAttr, 0, $pos));
                                        } else {
                                            $neuropathBis->$attr = $this->convertNumeric($valueAttr);
                                        }
                                    } else {
                                        $neuropathBis->id_donor = $this->convertNumeric($valueAttr);
                                    }
                                }
                            }
                        }
                    }
                }
                $neuropathBis->save();
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
                        $neuro->delete();
                    }
                }
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
        $type = "";
        $criteria = new EMongoCriteria;
        $criteria->login = "Bernard";
        $user = User::model()->find($criteria);
        $neuropath = Neuropath::model()->findAll();
        if ($neuropath != null) {
            foreach ($neuropath as $neuro) {
                $this->updateFicheNeuropath($neuro);
            }
        }
    }

    public function createFicheByNeuropathId($newNeuropath) {
        $type = "";
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
                            $typePrvmt = Prelevement::model()->findByAttributes(array('newColumn' => strtok($answerQuestion->label, '_')));
                            if ($type != null) {
                                $answerQuestion->type = ColumnFileMaker::model()->findByAttributes(array('newColumn' => $answerQuestion->label))->type;
                            } elseif ($typePrvmt != null) {
                                $answerQuestion->type = Prelevement::model()->findByAttributes(array('newColumn' => strtok($answerQuestion->label, '_')))->type;
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
                                } else {
                                    $answerQuestion->values = Prelevement::model()->findByAttributes(array('newColumn' => strtok($answerQuestion->label, '_')))->values;
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
                    $typePrvmt = Prelevement::model()->findByAttributes(array('newColumn' => strtok($answerQuestion->label, '_')));
                    if ($type != null) {
                        $answerQuestion->type = ColumnFileMaker::model()->findByAttributes(array('newColumn' => $answerQuestion->label))->type;
                    } elseif ($typePrvmt != null) {
                        $answerQuestion->type = Prelevement::model()->findByAttributes(array('newColumn' => strtok($answerQuestion->label, '_')))->type;
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
                        } else {
                            $answerQuestion->values = Prelevement::model()->findByAttributes(array('newColumn' => strtok($answerQuestion->label, '_')))->values;
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
                $this->createFicheByNeuropathId($newNeuropath);
                Yii::app()->user->setFlash('succès', 'La fiche a bien été accepté.');
                $this->redirect(array('fileImport/adminDoublon'));
            } elseif (isset($_GET['refuseAll'])) {
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
                $this->redirect(array('fileImport/adminDoublon'));
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
                    Yii::app()->user->setFlash('succès', 'La fiche a bien été passé en revue.');
                    $this->redirect(array('administration/index'));
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
        $neuropathBis = NeuropathBis::model()->findByAttributes(array('id_cbsd' => $idCbsd));
        $newNeuropath = new Neuropath;
        foreach ($neuropathBis as $k => $v) {
            if (isset($neuropath->$k)) {
                $newNeuropath->initSoftAttribute($k);
                $newNeuropath->$k = $neuropathBis->$k;
            } else {
                $newNeuropath->initSoftAttribute($k);
                $newNeuropath->$k = $v;
            }
        }
        $neuropath->delete();
        $newNeuropath->save();
        $neuropathBis->delete();
        return $newNeuropath;
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