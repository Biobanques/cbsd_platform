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
        $patient = (object) null;
        $dataPatient = simplexml_load_file(CommonProperties::$DATA_PATH);
        foreach ($dataPatient->children() as $samples) {
            $patient->id = null;
            $patient->source = 1; //à identifier en fonction de l'app
            $patient->sourceId = null;
            foreach ($samples->children() as $sample) {
                foreach ($sample->children() as $notes) {
                    foreach ($notes->children() as $note) {
                        if ($note->key == "birthName") {
                            $patient->birthName = (string) $note->value;
                        }
                        if ($note->key == "useName")
                            $patient->useName = (string) $note->value;
                        if ($note->key == "firstName")
                            $patient->firstName = (string) $note->value;
                        if ($note->key == "birthDate")
                            $patient->birthDate = (string) $note->value;
                        if ($note->key == "gender")
                            $patient->sex = (string) $note->value;
                    }
                    $patientest = CommonTools::wsGetPatient($patient);
                    if ($patientest == 'NoPatient')
                        $patient = CommonTools::wsAddPatient($patient);
                }
            }
        }
    }

}

?>