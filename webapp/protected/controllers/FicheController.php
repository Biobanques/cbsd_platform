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

    public function accessRules() {
        return array(
            array(
                'allow',
                'actions' => array(
                    'index', 'dynamicquestions'
                ),
                'users' => array(
                    '@'
                )
            ),
        );
    }

    /**
     * gestion des fiches.
     * Vue uniquement des formulaires renseignés par des utilisateurs.
     * vue tableau.
     */
    public function actionAdmin() {
        $model = new Answer('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Answer']))
            $model->attributes = $_GET['Answer'];

        $this->render('admin', array(
            'model' => $model,
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
        $model = Questionnaire::model()->findByPk(new MongoID($id));
        $model->delete();
// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }


}
