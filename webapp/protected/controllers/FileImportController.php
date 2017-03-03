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
                    'formatColumn'
                ),
                'expression' => '$user->getActiveProfil() == "administrateur"'
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
        if (isset($_POST['UploadedFile'])) {
            $uploadedFile->attributes = $_POST['UploadedFile'];
            $uploadedFile->filename = CUploadedFile::getInstance($uploadedFile, 'filename');
            $folderNominatif = CommonProperties::$IMPORT_FOLDER_NOMINATIF;
            if (substr($folderNominatif, -1) != '/') {
                $folderNominatif.='/';
            }
            chdir(Yii::app()->basePath . "/" . $folderNominatif);
            if ($uploadedFile->validate()) {
                $uploadedFile->filename->saveAs(date('Ymd_H') . 'h' . date('i') . '_' . $uploadedFile->filename->getName());
                chmod(date('Ymd_H') . 'h' . date('i') . '_' . $uploadedFile->filename->getName(), 0777);
                //$this->dropNeuropathCollection();
                $this->deleteNeuropathForms();
                $this->importNeuropathNominatif();
                $this->deleteUnvalidNeuropath();
                $this->createFicheNeuropath();
                $fileImport->user = Yii::app()->user->id;
                $fileImport->filename = date('Ymd_H') . 'h' . date('i') . '_' . $uploadedFile->filename->getName();
                $fileImport->filesize = $uploadedFile->filename->getSize();
                $fileImport->extension = $uploadedFile->filename->getExtensionName();
                $fileImport->date_import = DateTime::createFromFormat('d/m/Y', date('d/m/Y'));
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
        $this->render('formatColumn', array(
            'modelColumn' => $modelColumn
        ));
    }

    public function loadModel($id) {
        $model = UploadedFile::model()->findByPk(new MongoId($id));
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }
    
    public function dropNeuropathCollection() {
        return Neuropath::model()->deleteAll();
    }

    public function deleteNeuropathForms() {
        $criteria = new EMongoCriteria;
        $criteria->id = "neuropath_filemaker_form";
        return Answer::model()->deleteAll($criteria);
    }

    public function importNeuropathNominatif() {
        $countNotImported = 0;
        $attributes = array();
        $test = array();
        $files = array_filter(glob('*'), 'is_file');
        foreach ($files as $importedFile) {
            $pos = strpos($importedFile, '.');
            $file_pos = substr($importedFile, 0, $pos);
            if (file_exists("log/$file_pos.txt")) {
                unlink("log/$file_pos.txt");
            }
            $dataPatient = simplexml_load_file($importedFile);
            foreach ($dataPatient->children()->children() as $sample) {
                $neuropath = new Neuropath;
                $patient = (object) null;
                $patient->id = null;
                $patient->source = 1; // Banque de cerveaux
                $patient->sourceId = null;
                foreach ($sample->children() as $notes) {
                    foreach ($notes->children() as $note) {
                        switch ($note->key) {
                            case "id_donor":
                                $id_donor = (string) $note->value;
                                break;
                            case "birthName":
                                $patient->birthName = (string) $note->value;
                                break;
                            case "useName":
                                $patient->useName = (string) $note->value;
                                break;
                            case "firstName":
                                $pos = strpos((string) $note->value, ",");
                                if ($pos) {
                                    $patient->firstName = substr((string) $note->value, 0, $pos);
                                } else {
                                    $patient->firstName = (string) $note->value;
                                }
                                break;
                            case "birthDate":
                                $patient->birthDate = (string) $note->value;
                                break;
                            case "gender":
                                $patient->sex = (string) $note->value;
                                if ($patient->sex == null) {
                                    $patient->sex = "U";
                                }
                                if ($patient->sex == "M" && $patient->birthName == null && $patient->useName != null) {
                                    $patient->birthName = $patient->useName;
                                }
                                break;
                            default:
                                if (!in_array($note->key, $attributes)) {
                                    $attributes[(string) $note->key] = (string) $note->value;
                                }
                        }
                    }
                    if ($this->emptyFieldExist($patient) != true) {
                        $patientest = CommonTools::wsGetPatient($patient);
                        if ($patientest === 'NoPatient') {
                            $patient = CommonTools::wsAddPatient($patient);
                            $patientSIP = get_object_vars($patient);
                            foreach ($patientSIP as $k => $v) {
                                if ($k == "id") {
                                    $neuropath->initSoftAttribute("id_cbsd");
                                    $neuropath->id_cbsd = $v;
                                    foreach ($attributes as $key => $value) {
                                        $columnFileMaker = ColumnFileMaker::model()->findByAttributes(array('currentColumn' => $key))->newColumn;
                                        $neuropath->initSoftAttribute($columnFileMaker);
                                        $pos = strpos((string) $value, "-");
                                        if ($pos && $key == "braak_tau") {
                                            $neuropath->$columnFileMaker = $this->convertNumeric(substr($value, 0, $pos));
                                        } else {
                                            $neuropath->$columnFileMaker = $this->convertNumeric($value);
                                        }
                                    }
                                    $neuropath->save();
                                }
                            }
                        }
                    } else {
                        $countNotImported++;
                        $this->writePatientsNotImported($patient, $file_pos);
                    }
                }
            }

            if ($countNotImported > 0) {
                $this->log($countNotImported);
            }
            copy($importedFile, "treated/$importedFile");
            unlink($importedFile);
        }
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

    public function writePatientsNotImported($patient, $importedFile) {
        $file = "not_imported/$importedFile.txt";
        file_put_contents($file, print_r($patient, true), FILE_APPEND);
    }

    /*
     * Ecrit dans un fichier de log le nombre de patient qui n'ont pas été importé
     */

    public function log($countNotImported) {
        $log = "log/neuropath_nominatif.log";
        file_put_contents($log, "[" . date('d/m/Y H:i:s') . "] Nombre de patient qui n'ont pas été importé: " . $countNotImported . ".\n", FILE_APPEND);
    }

    public function deleteUnvalidNeuropath() {
        $neuropath = Neuropath::model()->findAll();
        foreach ($neuropath as $neuro) {
            if (!isset($neuro->id_cbsd)) {
                $neuro->delete();
            }
        }
    }

    public function createFicheNeuropath() {
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
                $answer->login = new MongoId($user->_id);
                $answer->questionnaireMongoId = new MongoId();
                $answer->name = "Import Neuropath";
                $answer->last_modified = DateTime::createFromFormat('d/m/Y', date('d/m/Y'));
                $answer->description = "Données neuropathologiques de la base FileMaker";
                $answer->last_updated = DateTime::createFromFormat('d/m/Y', date('d/m/Y'));
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
                            $answerQuestion->id = $k;
                            $answerQuestion->label = $k;
                            $answerQuestion->label_fr = $k;
                            $answerQuestion->type = ColumnFileMaker::model()->findByAttributes(array('newColumn' => $answerQuestion->label))->type;
                            $answerQuestion->style = "";
                            if ($answerQuestion->type == "date") {
                                $answerQuestion->answer = DateTime::createFromFormat('d/m/Y', date('d/m/Y', strtotime($v)));
                            } elseif ($answerQuestion->type == "number") {
                                $answerQuestion->answer = new MongoInt32($v);
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
            default: return $value;
        }
    }
}
