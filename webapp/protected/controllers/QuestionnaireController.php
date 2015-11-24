<?php

class QuestionnaireController extends Controller {

    /**
     *  NB : boostrap theme need this column2 layout
     *
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
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', 'actions' => array('index', 'view', 'viewOnePage', 'exportPDF'), 'users' => array('*'),
            ),
            array('allow', 'actions' => array('update'), 'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $model = $this->loadModel($id);
        $answer = null;
        if (isset($_POST['Questionnaire'])) {
            $answer = $this->saveQuestionnaireAnswers($model);
        }
        if ($answer != null)
            $model = $answer;
        if (isset($_SESSION['datapatient'])) {
            $patient = $_SESSION['datapatient'];
        }
        $this->render('view', array(
            'model' => $model,
            'patient' => $patient,
        ));
    }

    /**
     * Display to fill in questionnaire
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        $answer = null;
        if (isset($_POST['Questionnaire'])) {
            $answer = $this->saveQuestionnaireAnswers($model);
            $this->redirect('index.php?r=answer/affichepatient');
        }
        if ($answer != null)
            $model = $answer;
        if (isset($_SESSION['datapatient'])) {
            $patient = $_SESSION['datapatient'];
        }
        $this->render('update', array(
            'model' => $model,
            'patient' => $patient,
        ));
    }

    /**
     * save answers by this questionnaire.
     * for each question group then question save answer
     * //copy the questionnaire into answer
     * //then fill it with answers
     * @param questionnaire
     */
    public function saveQuestionnaireAnswers($model) {
        if (isset($_SESSION['datapatient'])) {
            $patients = $_SESSION['datapatient'];
        }
        $answer = new Answer;
        $answer->last_updated = new MongoDate();
        $answer->copy($model);
        $answer->type = $model->type;
        $answer->login = Yii::app()->user->id;
        $answer->id_patient = (string) $patients->id;
        $flagNoInputToSave = true;
        foreach ($answer->answers_group as $answer_group) {
            foreach ($answer_group->answers as $answerQuestion) {
                $input = $answer_group->id . "_" . $answerQuestion->id;
                if (isset($_POST['Questionnaire'][$input])) {
                    $flagNoInputToSave = false;
                    $answerQuestion->setAnswer($_POST['Questionnaire'][$input]);
                }
//if array, specific save action
                if ($answerQuestion->type == "array") {
//construct each id input an dget the result to store it
                    $rows = $answerQuestion->rows;
                    $arrows = split(",", $rows);
                    $cols = $answerQuestion->columns;
                    $arcols = split(",", $cols);
                    $answerArray = "";
                    foreach ($arrows as $row) {
                        foreach ($arcols as $col) {
                            $idunique = $idquestiongroup . "_" . $question->id . "_" . $row . "_" . $col;
                            if (isset($_POST['Questionnaire'][$idunique])) {
                                $answerArray.=$_POST['Questionnaire'][$idunique] . ",";
                            }
                        }
                    }
                    $answerQuestion->setAnswer($answerArray);
                }
            }
        }if ($flagNoInputToSave == false) {
            if ($answer->save())
                Yii::app()->user->setFlash('success', "La fiche a été sauvegardé avec succès.");
            else {
                Yii::app()->user->setFlash('error', "Le questionnaire n'a pas été sauvegardé.");
                Yii::log("pb save answer" . print_r($answer->getErrors()), CLogger::LEVEL_ERROR);
            }
        } else {
            Yii::app()->user->setFlash('error', "Le questionnaire n'a pas été sauvegardé.");
//null result
            $answer = null;
        }

        return $answer;
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
        QuestionnairePDFRenderer::render($this->loadModel($id));
    }

    /**
     * edit questionnaire : add/delete questions..
     * Not uyet implemented
     * @param integer $id the ID of the model to be updated
     */
    public function actionEdit($id) {
        Yii::app()->user->setFlash('warning', '<strong>Warning!</strong> Feature not available at this thime!.');
        $this->render('edit', array(
            'model' => $model,
            'form' => $form,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model = new Questionnaire;
        if (isset($_POST["form"])) {
            $criteria = new EMongoCriteria();
            $criteria->id = $_POST["form"];
            $id = Questionnaire::model()->find($criteria);
            $this->redirect('index.php?r=questionnaire/update&id=' . $id->_id);
        } else {
            Yii::app()->user->setFlash('warning', 'Veuillez sélectionner une fiche à remplir.');
            $this->redirect('index.php?r=answer/affichepatient');
        }
        $dataProvider = new EMongoDocumentDataProvider('Questionnaire');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Sample('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Questionnaire']))
            $model->attributes = $_GET['Questionnaire'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Questionnaire the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Questionnaire::model()->findByPk(new MongoID($id));
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Questionnaire $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'questionnaire-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
