<?php

/**
 * classe pour injecter les données de la base FileMaker vers le SIP.
 * La commande a executer et a mettre dans les cron task est :
 * ${PATH_TO_PROJECT}/protected/yiic importpatient
 * Exemple pour automatiser:
 * >crontab -e
 * >* * * * * /var/www/html/cbsd_platform/webapp/protected/yiic importpatient
 */
class MergeNeuropathDataCommand extends CConsoleCommand
{

    public function run($args)
    {
        $folderSource = CommonProperties::$IMPORT_FOLDER_NOMINATIF;
        if (substr($folderSource, -1) != '/') {
            $folderSource.='/';
        }
        chdir(Yii::app()->basePath . "/" . $folderSource . "saved/");
        $files = array_filter(glob('*'), 'is_file');
        echo count($files) . " files detected \n";
        foreach ($files as $importedFile) {
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
                            /*if ($note->key == "angiopathy_stage") {
                                $angiopathy_stage = (string) $note->value;
                            }
                            if ($note->key == "angiopathy_type") {
                                $angiopathy_stage = (string) $note->value;
                            }*/
                            if ($note->key == "signature_date") {
                                $signature_date = (string) $note->value;
                            }
                            if ($note->key == "family_tree") {
                                $family_tree = (string) $note->value;
                            }
                            if ($note->key == "detail_treatment") {
                                $detail_treatment = (string) $note->value;
                            }
                            if ($note->key == "associated_clinical_data") {
                                $associated_clinical_data = (string) $note->value;
                            }
                            if ($note->key == "associated_molecular_data") {
                                $associated_molecular_data = (string) $note->value;
                            }
                            if ($note->key == "associated_imagin_data") {
                                $associated_imagin_data = (string) $note->value;
                            }
                            if ($note->key == "quantity_available") {
                                $quantity_available = (string) $note->value;
                            }
                            if ($note->key == "biobank_collection_name") {
                                $biobank_collection_name = (string) $note->value;
                            }
                            if ($note->key == "trouble_start_date") {
                                $trouble_start_date = (string) $note->value;
                            }
                            if ($note->key == "first_trouble") {
                                $first_trouble = (string) $note->value;
                            }
                            if ($note->key == "mms") {
                                $mms = (string) $note->value;
                            }
                            if ($note->key == "id_sample") {
                                $id_sample = (string) $note->value;
                            }
                            if ($note->key == "collect_date") {
                                $collect_date = (string) $note->value;
                            }
                            if ($note->key == "diagnosis_2") {
                                $diagnosis_2 = (string) $note->value;
                            }
                            if ($note->key == "diagnosis_3") {
                                $diagnosis_3 = (string) $note->value;
                            }
                            if ($note->key == "diagnosis_4") {
                                $diagnosis_4 = (string) $note->value;
                            }
                            if ($note->key == "origin_sample_tissue") {
                                $origin_sample_tissue = (string) $note->value;
                            }
                            if ($note->key == "nature_sample_tissue") {
                                $nature_sample_tissue = (string) $note->value;
                            }
                            if ($note->key == "name_samples_tissue") {
                                $name_samples_tissue = (string) $note->value;
                            }
                            if ($note->key == "date_death") {
                                $date_death = (string) $note->value;
                            }
                            if ($note->key == "neuropathologist") {
                                $neuropathologist = (string) $note->value;
                            }
                            if ($note->key == "thal_amyloid") {
                                $thal_amyloid = (string) $note->value;
                            }
                            if ($note->key == "dft_harmonized") {
                                $dft_harmonized = (string) $note->value;
                            }
                            /*if ($note->key == "dm_basal_ganglia") {
                                $dm_basal_ganglia = (string) $note->value;
                            }
                            if ($note->key == "dm_frontal") {
                                $dm_frontal = (string) $note->value;
                            }
                            if ($note->key == "dm_hippocampal") {
                                $dm_hippocampal = (string) $note->value;
                            }
                            if ($note->key == "dm_temporal") {
                                $dm_temporal = (string) $note->value;
                            }
                            if ($note->key == "dm_total") {
                                $dm_total = (string) $note->value;
                            }*/
                        } 
                    }
                    if ($this->emptyFieldExist($patient) != true) {
                        $patientest = CommonTools::wsGetPatient($patient);
                        if (is_object($patientest)) {
                            $patientArray = get_object_vars($patientest);
                            foreach ($patientArray as $k=>$v){
                                if ($k == "id") {
                                    $criteria = new EMongoCriteria;
                                    $criteria->id = $v;
                                    $neuropath = Neuropath::model()->find($criteria);
                                    if ($neuropath != null) {
                                        /*$neuropath->Angiopathie_Amyloide_stade = $angiopathy_stage;
                                        $neuropath->Angiopathie_Amyloide_type = $angiopathy_type;*/
                                        $neuropath->signature_date = $signature_date;
                                        $neuropath->family_tree = $family_tree;
                                        $neuropath->detail_treatment = $detail_treatment;
                                        $neuropath->associated_clinical_data = $associated_clinical_data;
                                        $neuropath->associated_molecular_data = $associated_molecular_data;
                                        $neuropath->associated_imagin_data = $associated_imagin_data;
                                        $neuropath->quantity_available = $quantity_available;
                                        $neuropath->biobank_collection_name = $biobank_collection_name;
                                        $neuropath->trouble_start_date = $trouble_start_date;
                                        $neuropath->first_trouble = $first_trouble;
                                        $neuropath->mms = $mms;
                                        $neuropath->id_sample = $id_sample;
                                        $neuropath->collect_date = $collect_date;
                                        $neuropath->diagnosis_2 = $diagnosis_2;
                                        $neuropath->diagnosis_3 = $diagnosis_3;
                                        $neuropath->diagnosis_4 = $diagnosis_4;
                                        $neuropath->origin_sample_tissue = $origin_sample_tissue;
                                        $neuropath->nature_sample_tissue = $nature_sample_tissue;
                                        $neuropath->name_samples_tissue = $name_samples_tissue;
                                        $neuropath->date_death = $date_death;
                                        $neuropath->neuropathologist = $neuropathologist;
                                        $neuropath->thal_amyloid = $thal_amyloid;
                                        $neuropath->dft_harmonized = $dft_harmonized;
                                        /*$neuropath->Demence_vasculaire_Deramecourt_Basal_Ganglia = $dm_basal_ganglia;
                                        $neuropath->Demence_vasculaire_Deramecourt_Frontal = $dm_frontal;
                                        $neuropath->Demence_vasculaire_Deramecourt_Hippocampe = $dm_hippocampal;
                                        $neuropath->Demence_vasculaire_Deramecourt_Temporale = $dm_temporal;
                                        $neuropath->Demence_vasculaire_Deramecourt_total_Score = $dm_total;*/
                                       
                                        $neuropath->save();
                                       
                                    }
                                }
                            }
                        }

                    }
                }
            }
            copy($importedFile, "../treated/$importedFile");
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

