<?php

/**
 * classe pour injecter les données de la base FileMaker vers le SIP.
 * La commande a executer et a mettre dans les cron task est :
 * ${PATH_TO_PROJECT}/protected/yiic importpatient
 * Exemple pour automatiser:
 * >crontab -e
 * >* * * * * /var/www/html/cbsd_platform/webapp/protected/yiic importpatient
 */
class ExportUserSqlCommand extends CConsoleCommand {

    public function run($args) {
        $user = User::model()->findAll();
        $userAttributes = User::model()->getAttributes();
        $sql = "";
        $table = "user";
        $sql.= 'DROP TABLE IF EXISTS ' . $table . ';';
        $sql.= "\n";
        $sql.= 'CREATE TABLE ' . $table . ' (id int(11) NOT NULL AUTO_INCREMENT,';
        foreach ($userAttributes as $attributes => $v) {
            if ($attributes != "_id") {
                $sql.= $attributes . " varchar(100) NOT NULL,";
            }
        }
        $sql.= " PRIMARY KEY (id),";
        $sql = substr($sql, 0, -1);
        $sql.= ');';
        $sql.= "\n";
        foreach ($user as $k) {
            $sql.= 'INSERT INTO ' . $table . ' VALUES ("",';
            foreach ($k as $t => $u) {
                if ($t != "_id") {
                    if ($t != "profil") {
                        $sql.= '"' . $u . '",';
                    } else {
                        $sql.= '"profil",';
                    }
                }
            }
            $sql = substr($sql, 0, -1);
            $sql.= ');';
            $sql.= "\n";
        }
        echo $sql;
    }

}
