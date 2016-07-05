<?php

/**
 * classe pour injecter les données de la base FileMaker vers le SIP.
 * La commande a executer et a mettre dans les cron task est :
 * ${PATH_TO_PROJECT}/protected/yiic importpatient
 * Exemple pour automatiser:
 * >crontab -e
 * >* * * * * /var/www/html/cbsd_platform/webapp/protected/yiic importpatient
 */
class ExportQuestionTypeSqlCommand extends CConsoleCommand {

    public function run($args) {
        $questionForm = new QuestionForm;
        $types = $questionForm->getArrayTypes();
        $sql = "";
        $table = "list_question";
        $sql.= 'DROP TABLE IF EXISTS ' . $table . ';';
        $sql.= "\n";
        $sql.= 'CREATE TABLE ' . $table . ' (id int(11) NOT NULL AUTO_INCREMENT,type varchar(50) NOT NULL,PRIMARY KEY (id));';
        $sql.= "\n";
        foreach ($types as $type) {
            $sql.= 'INSERT INTO ' . $table . ' VALUES ("",' . $type . ',';
            $sql = substr($sql, 0, -1);
            $sql.= ');';
            $sql.= "\n";
        }
        echo $sql;
    }

}
