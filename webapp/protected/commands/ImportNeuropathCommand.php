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
        $folderNominatif = CommonProperties::$IMPORT_FOLDER_NOMINATIF;
        $folderAnonyme = CommonProperties::$IMPORT_FOLDER_ANONYME;
        $this->importNeuropathNominatif($folderNominatif);
        $this->importNeuropathAnonyme($folderAnonyme);
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
                    $neuropath->initSoftAttribute('id_anonymat');
                    $patient = (object) null;
                    $patient->id = null;
                    $patient->source = 1; // Banque de cerveaux
                    $patient->sourceId = null;
                    foreach ($sample->children() as $notes) {
                        foreach ($notes->children() as $note) {
                            switch ($note->key) {
                                case "id_donor":
                                    $patient->id_donor = (string) $note->value;
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
                                    $neuropath->initSoftAttribute((string) $note->key);
                                    $key = (string) $note->key;
                                    $neuropath->$key = (string) $note->value;
                            }
                        }
                        if ($this->emptyFieldExist($patient) != true) {
                            $patientest = CommonTools::wsGetPatient($patient);
                            if ($patientest === 'NoPatient') {
                                $patient = CommonTools::wsAddPatient($patient);
                                $neuropath->id_anonymat = $patient->id;
                                $neuropath->save();
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
            copy($importedFile, "treated/$importedFile");
            unlink($importedFile);
        }
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
            /* foreach ($dataPatient->RESULTSET as $result) {
              // récupère le nombre de données (RESULTSET)
              $res = $result['FOUND'];
              if ($res < $countNeuropath) {
              $this->fileNotImported();
              echo "Le fichier n'a pas été importé. Voir le log dans le dossier 'not_imported' pour plus de détails.\n";
              Yii::app()->end();
              }
              } */
            foreach ($dataPatient->METADATA->FIELD as $field) {
                $attribut[$i++] = $field['NAME'];
            }
            foreach ($dataPatient->children() as $samples) {
                foreach ($samples->children() as $sample) {
                    $neuropath = new Neuropath;
                    $patient = (object) null;
                    $patient->id = null;
                    $patient->source = 1; // Banque de cerveaux
                    $patient->sourceId = null;
                    foreach ($sample->children() as $notes) {
                        foreach ($notes->children() as $note) {
                            $var = str_replace(' ', '_', $attribut[$i]);
                            $var1 = str_replace("DonneurNonAnonyme::Nom_naissance", 'birthName', $var);
                            $var2 = str_replace('DonneurNonAnonyme::Prénoms', 'firstName', $var1);
                            $var3 = str_replace('DonneurNonAnonyme::patient_birth_date', 'birthDate', $var2);
                            $var4 = str_replace('DonneurNonAnonyme::Sexe', 'sex', $var3);
                            if ($var4 != "birthName" && $var4 != "firstName" && $var4 != "birthDate" && $var4 != "sex") {
                                $neuropath->initSoftAttribute($var4);
                                $neuropath->$var4 = (string) $note;
                                $attributes[$var4] = (string) $note;
                            }
                            if ($var4 == "birthName" || $var4 == "firstName" || $var4 == "birthDate" || $var4 == "sex") {
                                if ($note !== null) {
                                    if ($var4 == "firstName") {
                                        $pos = strpos((string) $note, ",");
                                        if ($pos) {
                                            $patient->$var4 = substr((string) $note, 0, $pos);
                                        } else {
                                            $patient->$var4 = (string) $note;
                                        }
                                    } else {
                                        $patient->$var4 = (string) $note;
                                    } if (isset($patient->sex) && $patient->sex == "M") {
                                        $patient->useName = $patient->birthName;
                                    } else {
                                        $patient->useName = null;
                                    }
                                }
                            }
                            $i++;
                        }
                    }
                    if (!$this->isEmpty($patient)) {
                        $patientest = CommonTools::wsGetPatient($patient);
                        if ($patientest === 'NoPatient') {
                            $patient = CommonTools::wsAddPatient($patient);
                        }
                        if (is_object($patientest)) {
                            $patientArray = get_object_vars($patientest);
                            foreach ($patientArray as $k => $v) {
                                if ($k == "id") {
                                    $criteria = new EMongoCriteria;
                                    $criteria->id_anonymat = $v;
                                    $neuro = Neuropath::model()->find($criteria);
                                    if ($neuro != null) {
                                        foreach ($attributes as $key => $value) {
                                            if (!isset($neuro->$key)) {
                                                $neuro->initSoftAttribute($key);
                                                $neuro->$key = $value;
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            $neuro = new Neuropath;                            
                            foreach ($attributes as $key => $value) {
                                $neuro->initSoftAttribute("id_anonymat");
                                $neuro->initSoftAttribute($key);
                                $neuro->id_anonymat = $patient->id;
                                $neuro->$key = $value;
                            }
                        }
                        $neuro->save();
                    }
                    $i = 0;
                }
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

}
