<?php

class AdministrationController extends Controller {

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
                    'index',
                ),
                'users' => array(
                    '@'
                )
            ),
        );
    }

    /**
     * action par defaut pour afficher des infos sur l administration et le menu.
     */
    public function actionIndex() {

        $this->render('index');
    }

    /**
     * gestion des formulaires
     */
    public function actionFormulaires() {
        $model = new Questionnaire('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Questionnaire']))
            $model->attributes = $_GET['Questionnaire'];

        $this->render('formulaires', array(
            'model' => $model,
        ));
    }

    /**
     * Affiche un formulaire ,en  lecture uniquement
     * @param $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $model = Questionnaire::model()->findByPk(new MongoID($id));
        $this->render('view_questionnaire', array(
            'model' => $model,
        ));
    }

    /**
     * Mise  àjour d un formulaire /mode edition
     * @param $id the ID of the model to be displayed
     */
    public function actionUpdate($id) {
        $model = Questionnaire::model()->findByPk(new MongoID($id));
        $questionForm = new QuestionForm;
        // collect user input data
        if (isset($_POST['QuestionForm'])) {
            $questionForm->attributes = $_POST['QuestionForm'];
            //traitement ajout de question
            $model = $this->saveQuestionnaireNewQuestion($model, $questionForm);
        }
        $questionForm->questionnaire = $model;

        $this->render('update_questionnaire', array(
            'model' => $model,
            'questionForm' => $questionForm
        ));
    }

    /**
     * Delete un formulaire
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $model = Questionnaire::model()->findByPk(new MongoID($id));
        $model->delete();
// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('formulaires'));
    }

    /**
     * save a new question into the questionnaire
     * @param questionnaire
     */
    public function saveQuestionnaireNewQuestion($questionnaire, $questionForm) {
        $questionnaire->last_modified = new MongoDate();
        foreach ($questionnaire->questions_group as $group) {
            foreach ($group->questions as $key => $question) {
                if ($question->id == $questionForm->idQuestionBefore) {
                    $question = new Question;
                    $question->setAttributesByQuestionForm($questionForm);
                    array_splice($group->questions, ($key+1), 0, array($question));
                    //$group->questions[]=$question; 
                }
            }
        }
        if ($questionnaire->save())
            Yii::app()->user->setFlash('success', "Formulaire enregistré avec sucès");
        else {
            Yii::app()->user->setFlash('error', "Formulaire non enregsiutré. Un problème est apparu.");
            Yii::log("pb save answer" . print_r($answer->getErrors()), CLogger::LEVEL_ERROR);
        }
        return $questionnaire;
    }

}
