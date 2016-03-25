<?php

Yii::import('ext.ECSVExport');

class RechercheFicheController extends Controller {

    /**
     * NB : boostrap theme need this column2 layout
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     *
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'view', 'exportCsv', 'exportXls', 'viewOnePage'),
                'expression' => '$user->getActiveProfil() != "clinicien"'
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Recherche des fiches disponibles.
     */
    public function actionAdmin() {
        $model = new Answer('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Answer']))
            $model->attributes = $_GET['Answer'];

        $this->render('admin', array(
            'model' => $model
        ));
    }

    /**
     * Affiche une fiche ,en  lecture uniquement
     * @param $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $model = Answer::model()->findByPk(new MongoID($id));
        $this->render('view', array(
            'model' => $model,
        ));
    }
    
    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionViewOnePage($id) {
        $model = Answer::model()->findByPk(new MongoID($id));
        $this->render('view_onepage', array(
            'model' => $model,
        ));
    }

    /**
     * export csv liste des fiches disponibles
     */
    public function actionExportCsv() {
        $model = new Answer('search');
        $model->unsetAttributes();
        if (isset($_GET['Answer']))
            $model->attributes = $_GET['Answer'];
        if (isset($_SESSION['criteria']) && $_SESSION['criteria'] != null && $_SESSION['criteria'] instanceof EMongoCriteria) {
            $criteria = $_SESSION['criteria'];
        } else {
            $criteria = new EMongoCriteria;
        }
        $models = Answer::model()->findAll($criteria);
        $filename = date('Ymd_H') . 'h' . date('i') . '_liste_fiches_CBSD_Platform.csv';
        $arAnswers = Answer::model()->resultToArray($models);
        $csv = new ECSVExport($arAnswers, true, false, null, null);
        Yii::app()->getRequest()->sendFile($filename, $csv->toCSV(), "text/csv", false);
    }

    /**
     * export xls listes des fiches disponibles
     * FIXME : pour l'instant meme algo que export csv car lib export xls moche ( non compat pages, libreoffice)
     */
    public function actionExportXls() {
        $model = new Answer('search');
        $model->unsetAttributes();
        if (isset($_GET['Answer']))
            $model->attributes = $_GET['Answer'];
        if (isset($_SESSION['criteria']) && $_SESSION['criteria'] != null && $_SESSION['criteria'] instanceof EMongoCriteria) {
            $criteria = $_SESSION['criteria'];
        } else {
            $criteria = new EMongoCriteria;
        }
        $models = Answer::model()->findAll($criteria);
        $filename = date('Ymd_H') . 'h' . date('i') . '_liste_fiches_CBSD_Platform.xls';
        $arAnswers = Answer::model()->resultToArray($models);
        $csv = new ECSVExport($arAnswers, true, false, null, null);
        Yii::app()->getRequest()->sendFile($filename, $csv->toCSV(), "text/xls", false);
        /*$model = new Answer('search');
        $model->unsetAttributes();
        if (isset($_GET['Answer']))
            $model->attributes = $_GET['Answer'];
        if (isset($_SESSION['criteria']) && $_SESSION['criteria'] != null && $_SESSION['criteria'] instanceof EMongoCriteria) {
            $criteria = $_SESSION['criteria'];
        } else {
            $criteria = new EMongoCriteria;
        }

        $models = Answer::model()->findAll($criteria);
        $lines = Answer::model()->resultToArray($models);
        $data = array();
        setlocale(LC_ALL, 'fr_FR.UTF-8');
        foreach ($lines as $fiche) {
            $line = array();
            foreach ($fiche as $value) {
                if ($value == 'null')
                    $line[] = null;
                else
                    $line[] = iconv("UTF-8", "ASCII//TRANSLIT", $value); //solution la moins pire qui ne fait pas bugge les accents mais les convertit en caractere generique
            }
            $data[] = $line;
        }
        Yii::import('application.extensions.phpexcel.JPhpExcel');
        $xls = new JPhpExcel('UTF-8', true, 'Liste des fiches disponibles');
        // $filename = date('Ymd_H').'h'.date('i').'_liste_fiches_CBSD_Platform.csv';

        $xls->addArray($data);
        $xls->generateXML('Liste des fiches disponibles');*/
    }
}
