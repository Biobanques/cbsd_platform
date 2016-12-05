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
            array('allow', 'actions' => array('index'), 'users' => array('*'),
            ),
            array('allow', 'actions' => array('update'), 'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
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
            $this->redirect(array('answer/affichepatient'));
        }
        if ($answer != null) {
            $model = $answer;
        }
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
        $answer->creator = ucfirst(Yii::app()->user->getPrenom()) . " " . strtoupper(Yii::app()->user->getNom());
        $answer->last_updated = DateTime::createFromFormat('d/m/Y', date('d/m/Y'));
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
                        if ($answerQuestion->type != "number" && $answerQuestion->type != "expression" && $answerQuestion->type != "date") {
                            $answerQuestion->setAnswer($_POST['Questionnaire'][$input]);
                        } elseif ($answerQuestion->type == "date") {
                            $answerQuestion->setAnswerDate($_POST['Questionnaire'][$input]);
                        } else {
                            $answerQuestion->setAnswerNumerique($_POST['Questionnaire'][$input]);
                        }
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
                Yii::app()->user->setFlash('success', Yii::t('common', 'patientFormSaved'));
            else {
                Yii::app()->user->setFlash('error', Yii::t('common', 'patientFormNotSaved'));
                Yii::log("pb save answer" . print_r($answer->getErrors()), CLogger::LEVEL_ERROR);
            }
        } else {
            Yii::app()->user->setFlash('error', Yii::t('common', 'patientFormNotSaved'));
//null result
            $answer = null;
        }

        return $answer;
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model = new Questionnaire;
        if (isset($_POST["form"]) && !empty($_POST['form'])) {
            $criteria = new EMongoCriteria();
            $criteria->id = $_POST["form"];
            $id = Questionnaire::model()->find($criteria);
            $this->redirect(array('questionnaire/update', 'id' => $id->_id));
        } else {
            Yii::app()->user->setFlash("error", Yii::t('common', 'selectPatientForm'));
            $this->redirect(array('answer/affichepatient'));
        }
        $dataProvider = new EMongoDocumentDataProvider('Questionnaire');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
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

