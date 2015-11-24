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
                'expression' => '$user->isAuthorized(Yii::app()->user->getActiveProfil(), Yii::app()->controller->id)'
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
        $dataProvider = array();
        $listAttributes = array();
        foreach ($models as $model) {
//récuperation de la liste totale des attributs
            foreach ($model->attributes as $attributeName => $attributeValue) {
                $listAttributes[$attributeName] = $attributeName;
            }
        }

        foreach ($models as $model) {
            $datas = array();
            foreach ($listAttributes as $attribute) {
                if (isset($model->$attribute)) {
                    if (!is_object($model->$attribute)) {
                        $datas[$attribute] = $model->$attribute;
                    } else
                        $datas[$attribute] = $model->$attribute;
                } else {
                    $datas[$attribute] = "";
                }
            }
            $dataProvider[] = $datas;
        }
        $filename = 'answers_list.csv';
        $csv = new ECSVExport($dataProvider);
        $toExclude = array();
        $toExport = $model->attributeExportedLabels();
        foreach ($listAttributes as $attribute) {

            if (!isset($toExport[$attribute]))
                $toExclude[] = $attribute;
        }
        $csv->setExclude($toExclude);
        Yii::app()->getRequest()->sendFile($filename, $csv->toCSV(), "text/csv", false);
    }

    /**
     * export xls listes des fiches disponibles
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

        $fiches = Answer::model()->findAll($criteria);
        $data = array(1 => array_keys(Answer::model()->attributeExportedLabels()));
        setlocale(LC_ALL, 'fr_FR.UTF-8');
        foreach ($fiches as $fiche) {
            $line = array();
            foreach (array_keys($fiche->attributeExportedLabels()) as $attribute) {

                if (isset($fiche->$attribute) && $fiche->$attribute != null && !empty($fiche->$attribute)) {
                    $line[] = iconv("UTF-8", "ASCII//TRANSLIT", $fiche->$attribute); //solution la moins pire qui ne fait pas bugge les accents mais les convertit en caractere generique
                } else {
                    $line[] = "-";
                }
            }
            $data[] = $line;
        }
        Yii::import('application.extensions.phpexcel.JPhpExcel');
        $xls = new JPhpExcel('UTF-8', true, 'Liste des fiches disponibles');
        $xls->addArray($data);
        $xls->generateXML('Liste des fiches disponibles');
    }

}
