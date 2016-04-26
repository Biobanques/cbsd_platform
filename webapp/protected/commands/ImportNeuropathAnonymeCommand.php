<?php

/**
 * classe pour injecter les données de la base FileMaker vers le SIP.
 * La commande a executer et a mettre dans les cron task est :
 * ${PATH_TO_PROJECT}/protected/yiic importpatient
 * Exemple pour automatiser:
 * >crontab -e
 * >* * * * * /var/www/html/cbsd_platform/webapp/protected/yiic importpatient
 */
class ImportNeuropathAnonymeCommand extends CConsoleCommand {

    public function run($args) {
        $attribut = array();
        $i = 0;
        $folderSource = CommonProperties::$TEST_IMPORT_ANONYME;
        if (substr($folderSource, -1) != '/') {
            $folderSource.='/';
        }
        chdir(Yii::app()->basePath . "/" . $folderSource);
        $files = array_filter(glob('*'), 'is_file');
        echo count($files) . " files detected \n";
        foreach ($files as $importedFile) {
            $dataPatient = simplexml_load_file($importedFile);
            foreach ($dataPatient->METADATA->FIELD as $test) {
                $attribut[$i++] = $test['NAME'];
            }
            foreach ($dataPatient->children() as $samples) {
                foreach ($samples->children() as $sample) {
                    $neuropath = new Neuropath;
                    foreach ($sample->children() as $notes) {
                        foreach ($notes->children() as $note) {
                            $var = str_replace(' ', '_', $attribut[$i]);
                            $var1 = str_replace("DonneurNonAnonyme::Nom_naissance", 'birthName', $var);
                            $var2 = str_replace('DonneurNonAnonyme::Prénoms', 'firstName', $var1);
                            $var3 = str_replace('DonneurNonAnonyme::patient_birth_date', 'birthDate', $var2);
                            $var4 = str_replace('DonneurNonAnonyme::Sexe', 'sex', $var3);
                            $neuropath->initSoftAttribute($var4);
                            if ($var4 == "firstName") {
                                $pos = strpos((string) $note, ",");
                                if ($pos) {
                                    $neuropath->$var4 = substr((string) $note, 0, $pos);
                                } else {
                                    $neuropath->$var4 = (string) $note;
                                }
                            } else {
                                $neuropath->$var4 = (string) $note;
                            }
                        }
                        $i++;
                    }
                    if (isset($neuropath->birthName) && isset($neuropath->firstName) && isset($neuropath->birthDate) && isset($neuropath->sex)) {
                        if ($this->isEmpty($neuropath) == false) {
                            $neuropath->save();
                            $patient = (object) null;
                            $patient->id = null;
                            $patient->source = 1; // Banque de cerveaux
                            $patient->sourceId = null;
                            $patient->birthName = $neuropath->birthName;
                            $patient->firstName = $neuropath->firstName;
                            $patient->birthDate = $neuropath->birthDate;
                            $patient->sex = $neuropath->sex;
                            $patient->useName = $neuropath->birthName;
                            $patientest = CommonTools::wsGetPatient($patient);
                            if ($patientest === 'NoPatient') {
                                $patient = CommonTools::wsAddPatient($patient);
                            }
                        } else {
                            $this->writePatientsNotImported($patient, $file_pos);
                        }
                    }
                    $i = 0;
                }
            }
            copy($importedFile, "treated/$importedFile");
            unlink($importedFile);
        }
    }

    public function isEmpty($neuropath) {
        if ($neuropath->birthName == null || $neuropath->firstName == null || $neuropath->birthDate == null || $neuropath->sex == null) {
            return true;
        } else {
            return false;
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
