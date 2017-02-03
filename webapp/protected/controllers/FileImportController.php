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
                    'admin'
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
                                        $var1 = str_replace('birthPlace', 'Lieu de naissance', $key);
                                        $var2 = str_replace('signature_date', 'Date de signature', $var1);
                                        $var3 = str_replace('family_tree', 'Arbre familial', $var2);
                                        $var4 = str_replace('detail_treatment', 'Détail du traitement', $var3);
                                        $var5 = str_replace("associated_clinical_data", 'Données cliniques associées', $var4);
                                        $var6 = str_replace('associated_molecular_data', 'Données moléculaires associées', $var5);
                                        $var7 = str_replace('associated_imagin_data', 'Associated imagin data', $var6);
                                        $var8 = str_replace('quantity_available', 'Quantité disponible', $var7);
                                        $var9 = str_replace('biobank_collection_name', 'Biobank collection name', $var8);
                                        $var10 = str_replace('trouble_start_date', 'Trouble start date', $var9);
                                        $var11 = str_replace('first_trouble', 'First trouble', $var10);
                                        $var12 = str_replace('mms', 'Mms', $var11);
                                        $var13 = str_replace('id_sample', 'Identifiant de l\'échantillon', $var12);
                                        $var14 = str_replace('collect_date', 'Collect date', $var13);
                                        $var15 = str_replace('diagnosis_1', 'Diagnostique 1', $var14);
                                        $var16 = str_replace('diagnosis_2', 'Diagnostique 2', $var15);
                                        $var17 = str_replace('diagnosis_3', 'Diagnostique 3', $var16);
                                        $var18 = str_replace('diagnosis_4', 'Diagnostique 4', $var17);
                                        $var19 = str_replace('origin_sample_tissue', 'Origin sample tissue', $var18);
                                        $var20 = str_replace('nature_sample_tissue', 'Nature sample tissue', $var19);
                                        $var21 = str_replace('name_samples_tissue', 'Name sample tissue', $var20);
                                        $var22 = str_replace('date_death', 'Date de décès', $var21);
                                        $var23 = str_replace('pmd', 'Pmd', $var22);
                                        $var24 = str_replace('city_brain_removal', 'City brain removal', $var23);
                                        $var25 = str_replace('neuropathologist', 'Neuropathologiste', $var24);
                                        $var26 = str_replace('braak_tau', 'Braak score', $var25);
                                        $var27 = str_replace('thal_amyloid', 'Thal score', $var26);
                                        $var28 = str_replace('lewy_type_kosaka', 'Lewy type Kosaka', $var27);
                                        $var29 = str_replace('braak_lewy', 'Braak Lewy', $var28);
                                        $var30 = str_replace('angiopathy_type', 'Angiopathy type', $var29);
                                        $var31 = str_replace('angiopathy_stage', 'Angiopathy stage', $var30);
                                        $var32 = str_replace('dft_harmonized', 'Dft Harmonized', $var31);
                                        $var33 = str_replace('dm_frontal', 'Dm frontal', $var32);
                                        $var34 = str_replace('dm_temporal', 'Dm temporal', $var33);
                                        $var35 = str_replace('dm_hippocampal', 'Dm hippocampal', $var34);
                                        $var36 = str_replace('dm_basal_ganglia', 'Dm basal ganglia', $var35);
                                        $var37 = str_replace('dm_total', 'Dm total', $var36);
                                        $neuropath->initSoftAttribute($var37);
                                        $pos = strpos((string) $value, "-");
                                        if ($pos) {
                                            $neuropath->$var37 = $this->convertNumeric(substr($value, 0, $pos));
                                        } else {
                                            $neuropath->$var37 = $this->convertNumeric($value);
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
