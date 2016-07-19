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
                                    $neuropath->initSoftAttribute("id");
                                    $neuropath->id = $v;
                                    $neuropath->initSoftAttribute("signature_date");
                                    $neuropath->initSoftAttribute("family_tree");
                                    $neuropath->initSoftAttribute("detail_treatment");
                                    $neuropath->initSoftAttribute("associated_clinical_data");
                                    $neuropath->initSoftAttribute("associated_molecular_data");
                                    $neuropath->initSoftAttribute("associated_imagin_data");
                                    $neuropath->initSoftAttribute("quantity_available");
                                    $neuropath->initSoftAttribute("biobank_collection_name");
                                    $neuropath->initSoftAttribute("trouble_start_date");
                                    $neuropath->initSoftAttribute("first_trouble");
                                    $neuropath->initSoftAttribute("mms");
                                    $neuropath->initSoftAttribute("id_sample");
                                    $neuropath->initSoftAttribute("collect_date");
                                    $neuropath->initSoftAttribute("diagnosis_2");
                                    $neuropath->initSoftAttribute("diagnosis_3");
                                    $neuropath->initSoftAttribute("diagnosis_4");
                                    $neuropath->initSoftAttribute("origin_sample_tissue");
                                    $neuropath->initSoftAttribute("nature_sample_tissue");
                                    $neuropath->initSoftAttribute("name_samples_tissue");
                                    $neuropath->initSoftAttribute("date_death");
                                    $neuropath->initSoftAttribute("neuropathologist");
                                    $neuropath->initSoftAttribute("thal_amyloid");
                                    $neuropath->initSoftAttribute("dft_harmonized");
                                }
                            }
                        }
                        $neuropath->save();
                    }
                    $i = 0;
                }
            }
            copy($importedFile, "treated/$importedFile");
            unlink($importedFile);
        }
    }

    public function isEmpty($patient) {
        if (!isset($patient->birthName) || !isset($patient->firstName) || !isset($patient->birthDate) || !isset($patient->sex)) {
            return true;
        } else {
            return false;
        }
    }

}
