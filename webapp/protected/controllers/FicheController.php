<?php

/**
 * controller de fiche pour la partie admin. Permet d aiguiller les actions sur l objet fiche côté admin.
 * @author nmalservet
 */
class FicheController extends Controller {

    /**
     * NB : boostrap theme need this column2 layout
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/menu_administration';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    public function accessRules() {
        return array(
            array(
                'allow',
                'actions' => array(
                    'index', 'dynamicquestions', 'admin', 'view', 'update', 'delete', 'viewOnePage', 'exportPDF'
                ),
                'expression' => '$user->getActiveProfil() == "Administrateur"'
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * gestion des fiches.
     * Vue uniquement des formulaires renseignés par des utilisateurs.
     * vue tableau.
     */
    public function actionAdmin() {
        $_SESSION['id_patientBis'] = null;
        $_SESSION['id_patientAll'] = null;
        $_SESSION['id_patient'] = null;
        $_SESSION['typeForm'] = null;
        $_SESSION['last_updated'] = null;
        $_SESSION['html'] = null;
        $_SESSION['formulateQuery'] = null;
        $_SESSION['Available'] = null;
        $model = new Answer('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Answer'])) {
            $model->attributes = $_GET['Answer'];
        }
        $modelUser = new User('search');
        $modelUser->unsetAttributes(); 
        if (isset($_GET['User'])) {
            $model->attributes = $_GET['User'];
        }   
        if (isset($_POST['rechercher'])) {
            if (isset($_POST['Fiche_id'])) {
                foreach($_POST['Fiche_id'] as $key => $value) {
                    $this->loadModel($value)->delete();
                    Yii::app()->user->setFlash('succès', Yii::t('patientForm', 'patientFormsDeleted'));
                }
            } else {
                Yii::app()->user->setFlash('erreur', Yii::t('patientForm', 'patientFormsNotDeleted'));
            }
        }
        $this->render('admin', array(
            'model' => $model,
            'modelUser' => $modelUser
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
     * Mise  àjour d une fiche
     * @param $id the ID of the model to be displayed
     */
    public function actionUpdate($id) {
        $model = Questionnaire::model()->findByPk(new MongoID($id));
        $questionForm = new QuestionForm;
        $questionGroup = new QuestionGroup;
        // collect user input data
        if (isset($_POST['QuestionForm'])) {
            $questionForm->attributes = $_POST['QuestionForm'];
            //traitement ajout de question
            if ($questionForm->validate()) {
                $model = $this->saveQuestionnaireNewQuestion($model, $questionForm);
            }
        }
        if (isset($_POST['QuestionGroup'])) {
            $questionGroup->attributes = $_POST['QuestionGroup'];
            //copie du titre sur l option fr
            $questionGroup->title_fr = $questionGroup->title;
            if ($questionGroup->validate()) {
                $model = $this->saveQuestionnaireNewGroup($model, $questionGroup);
            }
        }
        //set du model sur la questionForm pour generer l arborescende de position de question
        $questionForm->questionnaire = $model;
        $this->render('update', array(
            'model' => $model,
            'questionForm' => $questionForm,
            'questionGroup' => $questionGroup
        ));
    }

    /**
     * Suppression d une fiche
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $model = Answer::model()->findByPk(new MongoID($id));
        try {
            $model->delete();
            if (!isset($_GET['ajax'])) {
                Yii::app()->user->setFlash('succès', Yii::t('common', 'patientFormDeleted'));
            } else {
                echo "<div class='flash-success'>" . Yii::t('common', 'patientFormDeleted') . "</div>"; //for ajax
            }
        } catch (CDbException $e) {
            if (!isset($_GET['ajax'])) {
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'patientFormNotDeleted'));
            } else {
                echo "<div class='flash-error'>" . Yii::t('common', 'patientFormNotDeleted') . "</div>";
            } //for ajax
        }
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionViewOnePage($id) {
        $this->render('view_onepage', array(
            'model' => $this->loadModel($id),
        ));
    }
    
    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionExportPDF($id) {
        AnswerPDFRenderer::renderAnswer($this->loadModel($id));
    }
    
    public function loadModel($id) {
        $model = Answer::model()->findByPk(new MongoID($id));
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }
}
