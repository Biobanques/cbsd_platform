<?php

/**
 * classe pour injecter les données de la base FileMaker vers le SIP.
 * La commande a executer et a mettre dans les cron task est :
 * ${PATH_TO_PROJECT}/protected/yiic importpatient
 * Exemple pour automatiser:
 * >crontab -e
 * >* * * * * /var/www/html/cbsd_platform/webapp/protected/yiic importpatient
 */
class ImportPatientCommand extends CConsoleCommand
{

    public function run($args)
    {
        $folderSource = CommonProperties::$MASS_IMPORT_FOLDER;
        if (substr($folderSource, -1) != '/') {
            $folderSource.='/';
        }
        chdir(Yii::app()->basePath . "/" . $folderSource);
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
                    $patient = (object) null;
                    $patient->id = null;
                    $patient->source = 1; // Banque de cerveaux
                    $patient->sourceId = null;
                    foreach ($sample->children() as $notes) {
                        foreach ($notes->children() as $note) {
                            if ($note->key == "id_donor") {
                                $patient->id_donor = (string) $note->value;
                            }
                            if ($note->key == "birthName") {
                                $patient->birthName = (string) $note->value;
                            }
                            if ($note->key == "useName") {
                                $patient->useName = (string) $note->value;
                            }
                            if ($note->key == "firstName") {
                                $pos = strpos((string) $note->value, ",");
                                if ($pos) {
                                    $patient->firstName = substr((string) $note->value, 0, $pos);
                                } else {
                                    $patient->firstName = (string) $note->value;
                                }
                            }
                            if ($note->key == "birthDate") {
                                $patient->birthDate = (string) $note->value;
                            }
                            if ($note->key == "gender") {
                                $patient->sex = (string) $note->value;
                                if ($patient->sex == null) {
                                    $patient->sex = "U";
                                }
                                if ($patient->sex == "M" && $patient->birthName == null && $patient->useName != null) {
                                    $patient->birthName = $patient->useName;
                                }
                            }
                        }
                        if ($this->emptyFieldExist($patient) != true) {
                            $patientest = CommonTools::wsGetPatient($patient);
                            if ($patientest === 'NoPatient') {
                                $patient = CommonTools::wsAddPatient($patient);
                            }
                        } else {
                            $this->writePatientsNotImported($patient, $file_pos);
                        }
                    }
                }
            }
            copy($importedFile, "treated/$importedFile");
            unlink($importedFile);
        }
    }

    /*
     * Vérifie si un des champs "birthName", "useName", "firstName", "birthDate", "gender" est vide
     */

    public function emptyFieldExist($patient)
    {
        foreach ($patient as $field => $value) {
            if ($field != "id" && $field != "sourceId" && empty($value)) {
                return true;
            }
        }
    }

    /*
     * Ecrit dans un fichier les patients qui n'ont pas pu être importé ("A SIP item is missing in the file")
     */

    public function writePatientsNotImported($patient, $importedFile)
    {
        $file = "not_imported/$importedFile.txt";
        file_put_contents($file, print_r($patient, true), FILE_APPEND);
    }
}
