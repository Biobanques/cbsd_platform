<?php

/**
 * classe pour injecter les données de la base FileMaker vers le SIP.
 * La commande a executer et a mettre dans les cron task est :
 * ${PATH_TO_PROJECT}/protected/yiic importpatient
 * Exemple pour automatiser:
 * >crontab -e
 * >* * * * * /var/www/html/cbsd_platform/webapp/protected/yiic importpatient
 */
class ImportPatientCommand extends CConsoleCommand {

    public function run($args) {
        $dataPatient = simplexml_load_file(CommonProperties::$DATA_PATH);
        foreach ($dataPatient->children() as $samples) {
            foreach ($samples->children() as $sample) {
                $patient = (object) null;
                $patient->id = null;
                $patient->source = 1; // Banque de cerveaux
                $patient->sourceId = null;
                foreach ($sample->children() as $notes) {
                    foreach ($notes->children() as $note) {
                        if ($note->key == "birthName") {
                            $patient->birthName = (string) $note->value;
                        }
                        if ($note->key == "useName") {
                            $patient->useName = (string) $note->value;
                        }
                        if ($note->key == "firstName") {
                            $patient->firstName = (string) $note->value;
                        }
                        if ($note->key == "birthDate") {
                            $patient->birthDate = (string) $note->value;
                        }
                        if ($note->key == "gender") {
                            $patient->sex = (string) $note->value;
                        }
                    }
                    if ($this->emptyFieldExist($patient) != true) {
                        $patientest = CommonTools::wsGetPatient($patient);
                        if ($patientest == 'NoPatient')
                            $patient = CommonTools::wsAddPatient($patient);
                    }
                }
            }
        }
    }

    public function emptyFieldExist($patient) {
        foreach ($patient as $field => $value) {
            if ($field != "id" && $field != "sourceId" && empty($value)) {
                return true;
            }
        }
    }

}

?>