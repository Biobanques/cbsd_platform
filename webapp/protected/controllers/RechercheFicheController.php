<?php

Yii::import('ext.ECSVExport');

class RechercheFicheController extends Controller {

    /**
     * NB : boostrap theme need this column2 layout
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     *
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'view', 'exportCsv', 'resultSearch', 'viewOnePage'),
                'expression' => '!Yii::app()->user->isGuest && $user->getActiveProfil() != "clinicien"'
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Recherche des fiches disponibles.
     */
    public function actionAdmin() {
        $model = new Answer('search');
        $fileImport = new FileImport;
        $uploadedFile = new UploadedFile;
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Answer']))
            $model->attributes = $_GET['Answer'];
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
                $this->dropNeuropathCollection();
                $this->deleteNeuropathForms();
                $this->importNeuropathNominatif($folderNominatif);
                $this->deleteUnvalidNeuropath();
                $this->createFicheNeuropath();
                $fileImport->user = Yii::app()->user->id;
                $fileImport->filename = date('Ymd_H') . 'h' . date('i') . '_' . $uploadedFile->filename->getName();
                $fileImport->filesize = $uploadedFile->filename->getSize();
                $fileImport->extension = $uploadedFile->filename->getExtensionName();
                $fileImport->date_import = DateTime::createFromFormat('d/m/Y', date('d/m/Y'));
                $fileImport->save();
                Yii::app()->user->setFlash('success', 'La base FileMaker a bien été importé !');
            }
        }
        $this->render('admin', array(
            'model' => $model,
            'uploadedFile' => $uploadedFile
        ));
    }

    /**
     * Affiche une fiche ,en  lecture uniquement
     * @param $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $model = Answer::model()->findByPk(new MongoID($id));
        $this->render('view', array(
            'model' => $model,
        ));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionViewOnePage($id) {
        $model = Answer::model()->findByPk(new MongoID($id));
        $this->render('view_onepage', array(
            'model' => $model,
        ));
    }

    /**
     * export csv liste des fiches disponibles
     */
    public function actionExportCsv() {
        if (isset($_POST['exporter'])) {
            $filter = array();
            if (isset($_POST['filter'])) {
                $filter = $_POST['filter'];
            }
            $filename = date('Ymd_H') . 'h' . date('i') . '_liste_fiches_CBSD_Platform.csv';
            $arAnswers = Answer::model()->resultToArray($_SESSION['models'], $filter);
            $csv = new ECSVExport($arAnswers, true, false, null, null);
            Yii::app()->getRequest()->sendFile($filename, "\xEF\xBB\xBF" . $csv->toCSV(), "text/csv; charset=UTF-8", false);
        }
        $model = new Answer('search');
        $model->unsetAttributes();
        if (isset($_GET['Answer'])) {
            $model->attributes = $_GET['Answer'];
        }
        if (isset($_SESSION['criteria']) && $_SESSION['criteria'] != null && $_SESSION['criteria'] instanceof EMongoCriteria) {
            $criteria = $_SESSION['criteria'];
        } else {
            $criteria = new EMongoCriteria;
        }
        // trier par id_patient et type de fiche dans l'ordre croissant
        $criteria->sort('id_patient', EMongoCriteria::SORT_ASC);
        $criteria->sort('type', EMongoCriteria::SORT_ASC);
        $models = Answer::model()->findAll($criteria);
        $_SESSION['models'] = $models;
        if (count($models) < 1) {
            Yii::app()->user->setFlash(TbAlert::TYPE_ERROR, Yii::t('common', 'emptyPatientFormExport'));
            $this->redirect(array("rechercheFiche/admin"));
        }
        $this->render('exportFilter', array(
            'models' => $models,
        ));
    }

    public function actionResultSearch() {
        $idPatient = array();
        $model = new Answer('search');
        $model->unsetAttributes();
        if (isset($_GET['Answer'])) {
            $model->attributes = $_GET['Answer'];
        }
        if (isset($_POST['Answer_id_patient'])) {
            $criteria = new EMongoCriteria;
            $regex = '/^';
            foreach ($_POST['Answer_id_patient'] as $idPatient) {
                $regex.= $idPatient . '$|^';
            }
            $regex .= '$/i';
            $criteria->addCond('id_patient', '==', new MongoRegex($regex));
            $_SESSION['id_patient'] = $regex;
        }
        $this->render('result_search', array(
            'model' => $model
        ));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionExportPDF($id) {
        AnswerPDFRenderer::renderAnswer($this->loadModel($id));
    }

    public function loadModel($id) {
        $model = Answer::model()->findByPk(new MongoID($id));
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
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

    public function importNeuropathNominatif($folderNominatif) {
        $countNotImported = 0;
        $attributes = array();
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
                                        $neuropath->initSoftAttribute($key);
                                        $neuropath->$key = $value;
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
        $file = "log/$importedFile.txt";
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
                            if (is_numeric($v)) {
                                $answerQuestion->type = "number";
                            } elseif (CommonTools::isDate($v)) {
                                $answerQuestion->type = "date";
                            } else {
                                $answerQuestion->type = "input";
                            }
                            $answerQuestion->style = "";
                            if ($answerQuestion->type == "date") {
                                $answerQuestion->answer = DateTime::createFromFormat('d/m/Y', date('d/m/Y', strtotime($v)));
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

}
