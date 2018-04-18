<?php

/**
 * classe pour injecter les données de la base FileMaker vers le SIP.
 * La commande a executer et a mettre dans les cron task est :
 * ${PATH_TO_PROJECT}/protected/yiic importneuropath
 * Exemple pour automatiser:
 * >crontab -e
 * >* * * * * /var/www/html/cbsd_platform/webapp/protected/yiic importneuropath
 */
class ImportNeuropathCommand extends CConsoleCommand {

    public function run($args) {
        //$folderNominatif = CommonProperties::$IMPORT_FOLDER_NOMINATIF;
        $folderAnonyme = CommonProperties::$IMPORT_FOLDER_ANONYME;
        //$this->dropNeuropathCollection();
        //$this->deleteNeuropathForms();
        $this->importNeuropathAnonyme($folderAnonyme);
        //$this->importNeuropathNominatif($folderNominatif);
        //$this->deleteUnvalidNeuropath();
       //$this->createFicheNeuropath();
    }

    public function dropNeuropathCollection() {
        return Neuropath::model()->deleteAll();
    }

    public function deleteNeuropathForms() {
        $criteria = new EMongoCriteria;
        $criteria->id = "neuropath_filemaker_form";
        return Answer::model()->deleteAll($criteria);
    }

    public function importNeuropathAnonyme($folderAnonyme) {
        $attribut = array();
        $attributes = array();
        $res = 0;
        $i = 0;
        $countNeuropath = count(Neuropath::model()->findAll());
        $folderAnonyme = CommonProperties::$IMPORT_FOLDER_ANONYME;
        if (substr($folderAnonyme, -1) != '/') {
            $folderAnonyme.='/';
        }
        chdir(Yii::app()->basePath . "/" . $folderAnonyme);
        $files = array_filter(glob('*'), 'is_file');
        echo count($files) . " files detected \n";
        foreach ($files as $importedFile) {
            $dataPatient = simplexml_load_file($importedFile);
            foreach ($dataPatient->children() as $samples) {
                foreach ($samples->children() as $sample) {
                    $neuropath = new Neuropath;
                    $patient = (object) null;
                    $patient->id = null;
                    $patient->source = 1; // Banque de cerveaux
                    $patient->sourceId = null;
                    $i = 0;
                    $nbSample = 0;
                    foreach ($sample->children() as $notes) {
                        $nbAttributes = count($notes);
                        //print_r($notes);
                        while ($i < $nbAttributes - 1) {
                            $value = get_object_vars($notes->note[$i]);
                            $neuropath->initSoftAttribute($value["key"]);
                            if (!empty($value["value"])) {
                                $neuropath->$value["key"] = $value["value"];
                            } else {
                                $neuropath->$value["key"] = null;
                            }
                            $neuropath->save();
                            $i++;
                        }
                        if ($i == 36) {
                            $value = get_object_vars($notes->note[$i]);
                            if ($value != null) {
                                $nbSample = count($value["Origin_Sample_Tissue"]);
                                $index = 0;
                                while ($index < $nbSample) {
                                    if (!empty($value["Congélation"][$index])) {
                                        $attr = str_replace(".", "", $value["Origin_Sample_Tissue"][$index] . "_" . $value["Congélation"][$index]);
                                        $neuropath->initSoftAttribute($attr);
                                        $neuropath->$attr = $value["Quantité_disponible"][$index];
                                        $neuropath->save();
                                    } else {
                                        $attr = "";
                                    }
                                    $index++;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    
    public function importNeuropathNominatif($folderNominatif) {
        $countNotImported = 0;
        $attributes = array();
        if (substr($folderNominatif, -1) != '/') {
            $folderNominatif.='/';
        }
        chdir(Yii::app()->basePath . "/" . $folderNominatif);
        $files = array_filter(glob('*'), 'is_file');
        echo count($files) . " files detected \n";
        foreach ($files as $importedFile) {
            $pos = strpos($importedFile, '.');
            $file_pos = substr($importedFile, 0, $pos);
            if (file_exists("not_imported/$file_pos.txt")) {
                unlink("not_imported/$file_pos.txt");
            }
            $dataPatient = simplexml_load_file($importedFile);
            foreach ($dataPatient->children() as $samples) {
                foreach ($samples->children() as $sample) {
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
                                        $criteria = new EMongoCriteria;
                                        $criteria->id_donor = $id_donor;
                                        $neuropath = Neuropath::model()->find($criteria);
                                        if ($neuropath != null) {
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
                            }
                        } else {
                            $countNotImported++;
                            $this->writePatientsNotImported($patient, $file_pos);
                        }
                    }
                }
            }
            if ($countNotImported > 0) {
                $this->log($countNotImported);
            }
            //copy($importedFile, "treated/$importedFile");
            //unlink($importedFile);
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

    public function isEmpty($patient) {
        if (!isset($patient->birthName) || !isset($patient->firstName) || !isset($patient->birthDate) || !isset($patient->sex)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Fichier de log
     */

    public function fileNotImported() {
        $file = "not_imported/neuropath_anonyme.log";
        file_put_contents($file, "[" . date('d/m/Y H:i:s') . "] Le fichier n'a pas été importé car la taille des données dans le fichier est inférieure à la taille des données dans la base mongodb.\n", FILE_APPEND);
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
        $log = "not_imported/neuropath_nominatif.log";
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
        $index = 0;
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
                                $answerQuestion->answer = DateTime::createFromFormat(CommonTools::FRENCH_SHORT_DATE_FORMAT, date(CommonTools::FRENCH_SHORT_DATE_FORMAT, strtotime($v)));
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
