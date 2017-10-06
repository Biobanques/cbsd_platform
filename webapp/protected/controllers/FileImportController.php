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
                    'update',
                    'delete',
                    'formatColumn',
                    'exportNonImported'
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
                $this->deleteImportNeuropathForms();
                $this->createFicheNeuropath();
                $fileImport->user = Yii::app()->user->id;
                $fileImport->filename = $date . '_' . $uploadedFile->filename->getName();
                $fileImport->filesize = $uploadedFile->filename->getSize();
                $fileImport->extension = $uploadedFile->filename->getExtensionName();
                $fileImport->date_import = DateTime::createFromFormat(CommonTools::FRENCH_SHORT_DATE_FORMAT, date(CommonTools::FRENCH_SHORT_DATE_FORMAT));
                $fileImport->imported = $_SESSION['countImported'];
                $fileImport->not_imported = $_SESSION['countNotImported'];
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
        $countImported = 0;
        $countNotImported = 0;
        $_SESSION['countImported'] = null;
        $_SESSION['countNotImported'] = null;
        $attributes = array();
        $test = array();
        $res = array();
        $_SESSION['patientFM'] = null;
        $fp = fopen($uploadedFile->filename, 'r');
        $rows = array_map('str_getcsv', file($uploadedFile->filename));
        $header = array_shift($rows);
        $csv = array();
        foreach ($rows as $row) {
            $csv[] = array_combine($header, $row);
        }
        foreach ($csv as $kCSV => $vCSV) {
            $test = array();
            $attributes = array();
            $exist = false;
            $_SESSION['test'] = null;
            $_SESSION['qte'] = null;
            $neuropath = new Neuropath;
            $patient = (object) null;
            $patient->id = null;
            $patient->source = 1; // Banque de cerveaux
            $patient->sourceId = null;
            $patient->birthName = null;
            $patient->useName = null;
            $patient->firstName = null;
            $patient->birthDate = null;
            $patient->sex = null;
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
                    case "_DateNaissance":
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
            $test[$attributes['PrelevementTissusTranche::Origin_Samples_Tissue']] = $attributes['PrelevementTissusTranche::quantity_available'];
            $_SESSION['test'] = $attributes['PrelevementTissusTranche::Origin_Samples_Tissue'];
            $_SESSION['qte'] = $attributes['PrelevementTissusTranche::quantity_available'];
            unset($attributes['PrelevementTissusTranche::Origin_Samples_Tissue']);
            unset($attributes['PrelevementTissusTranche::quantity_available']);
            $res = array_merge($attributes, $test);
            if ($patient->birthName == null && $patient->useName == null && $patient->firstName == null && $patient->birthDate == null && $patient->sex == "U") {
                $patient->birthName = $_SESSION['patientFM']->birthName;
                $patient->useName = $_SESSION['patientFM']->useName;
                $patient->firstName = $_SESSION['patientFM']->firstName;
                $patient->birthDate = $_SESSION['patientFM']->birthDate;
                $patient->sex = $_SESSION['patientFM']->sex;
            }
            if ($this->emptyFieldExist($patient) != true) {
                $_SESSION['patientFM'] = $patient;
                $patientest = CommonTools::wsGetPatient($patient);
                if ($patientest === 'NoPatient') {
                    $patient = CommonTools::wsAddPatient($patient);
                    $patientSIP = get_object_vars($patient);
                    foreach ($patientSIP as $kSIP => $vSIP) {
                        if ($kSIP == "id" && $vSIP != "") {
                            $neuropath->initSoftAttribute("id_cbsd");
                            $neuropath->id_cbsd = $vSIP;
                            foreach ($res as $keyAttr => $valueAttr) {
                                if ($keyAttr != null || $keyAttr != "") {
                                    $columnFileMaker = ColumnFileMaker::model()->findByAttributes(array('currentColumn' => $keyAttr));
                                    if ($columnFileMaker != null) {
                                        $fileMaker = $columnFileMaker->newColumn;
                                        $neuropath->initSoftAttribute($fileMaker);
                                        $pos = strpos((string) $valueAttr, "-");
                                        if (($pos && $keyAttr == "PrelevementTissus::Braak_Tau") || ($pos && $keyAttr == "PrelevementTissus::Thal_amiloide")) {
                                            $neuropath->$fileMaker = $this->convertNumeric(substr($valueAttr, 0, $pos));
                                        } else {
                                            $neuropath->$fileMaker = $this->convertNumeric($valueAttr);
                                        }
                                    } else {
                                        $neuropath->initSoftAttribute($keyAttr);
                                        if (($pos && $keyAttr == "PrelevementTissus::Braak_Tau") || ($pos && $keyAttr == "PrelevementTissus::Thal_amiloide")) {
                                            $neuropath->$keyAttr = $this->convertNumeric(substr($valueAttr, 0, $pos));
                                        } else {
                                            $neuropath->$keyAttr = $this->convertNumeric($valueAttr);
                                        }
                                    }
                                }
                            }
                            $neuropath->save();
                            $countImported++;
                        }
                    }
                }  else {
                    $patientSIP = get_object_vars($patientest);
                    foreach ($patientSIP as $k => $v) {
                        if ($k == "id") {
                            $criteria = new EMongoCriteria;
                            $criteria->id_cbsd = $v;
                            $neuropath = Neuropath::model()->find($criteria);
                            if ($neuropath != null && $_SESSION['test'] != null) {
                                foreach ($neuropath as $key => $value) {
                                    if ($key == $_SESSION['test']) {
                                        $exist = true;
                                    }
                                }
                                if (!$exist) {
                                    $_SESSION['test'] = str_replace('.', '', $_SESSION['test']);
                                    $neuropath->initSoftAttribute($_SESSION['test']);
                                    $neuropath->$_SESSION['test'] = $_SESSION['qte'];
                                    $neuropath->save();
                                }
                            }
                        }
                    }
                }
            } else {
                $countNotImported++;
                //$this->writePatientsNotImported($patient, $uploadedFile);
            }
        }
        if ($countImported > 0) {
            $_SESSION['countImported'] = $countImported;
        } else {
            $_SESSION['countImported'] = 0;
        }
        if ($countNotImported > 0) {
            $_SESSION['countNotImported'] = $countNotImported;
        } else {
            $_SESSION['countNotImported'] = 0;
        }
        //copy($importedFile, "treated/$importedFile");
        //unlink($importedFile);
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
                foreach ($neuro as $k => $v) {
                    if ($k != "_id") {
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
                                $answerQuestion->answer = new MongoInt32($v);
                            } elseif ($answerQuestion->type == "radio") {
                                $answerQuestion->values = ColumnFileMaker::model()->findByAttributes(array('newColumn' => $answerQuestion->label))->values;
                                $answerQuestion->answer = $v;
                            } else {
                                $answerQuestion->answer = $v;
                            }
                            $answerGroup->answers[] = $answerQuestion;
                        }
                    }
                }
                $answer->answers_group[] = $answerGroup;
                $answer->save();
            }
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
            default: if (ctype_digit($value)) return (int) $value; else return $value;
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
