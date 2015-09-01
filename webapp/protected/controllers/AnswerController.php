<?php

class AnswerController extends Controller
{
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
            array('allow', 'users' => array('@'),
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
        if (isset($_SESSION['datapatient'])) {
            $patient = $_SESSION['datapatient'];
            $this->render('view', array(
                'model' => $model,
                'patient' => $patient,
            ));
        }
    }

    public function actionAffichepatient() {
        $model = new Patient;
        if (isset($_POST['Patient'])) {
            $model->attributes = $_POST['Patient'];
            $patient = (object) null;
            $patient->id = null;
            $patient->source = '1'; //à identifier en fonction de l'app
            $patient->sourceId = null;
            $patient->birthName = $model->nom_naissance;
            $patient->useName = $model->nom;
            $patient->firstName = $model->prenom;
            $patient->birthDate = $model->date_naissance;
            $patient->sex = $model->sexe;
            $patient->id = CommonTools::wsGetPatient($patient);
            if ($patient->id === -1)
                Yii::app()->user->setFlash(TbAlert::TYPE_ERROR, "An error occured with search, please contact webabb admin");
            $model->id = $patient->id;
        }

        $criteria = new EMongoCriteria();
        $criteria->login = Yii::app()->user->id;

        $dataProvider = new EMongoDocumentDataProvider('Answer', array('criteria' => $criteria));
        $_SESSION['datapatient'] = $patient;
        $this->render('affichepatient', array('model' => $model, 'dataProvider' => $dataProvider));
        //$this->render('affichepatient', array('model' => $model));
    }

    /**
     * Display to update answers
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        if (isset($_POST['Questionnaire'])) {
            $model->last_updated = new MongoDate();
            $flagNoInputToSave = true;
            foreach ($model->answers_group as $answer_group) {
                foreach ($answer_group->answers as $answerQuestion) {
                    $input = $answer_group->id . "_" . $answerQuestion->id;
                    if (isset($_POST['Questionnaire'][$input])) {
                        $flagNoInputToSave = false;
                        $answerQuestion->setAnswer($_POST['Questionnaire'][$input]);
                    }
                }
            }if ($flagNoInputToSave == false) {
                if ($model->save())
                    Yii::app()->user->setFlash('success', "Document saved with success");
                else {
                    Yii::app()->user->setFlash('error', "Document not saved. A problem occured.");
                    Yii::log("pb save answer" . print_r($answer->getErrors()), CLogger::LEVEL_ERROR);
                }
            } else {
                Yii::app()->user->setFlash('error', "Document not saved. No Input to save.");
            }
        }
        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $criteria = new EMongoCriteria;
        $criteria->login = Yii::app()->user->id;
        $dataProvider = new EMongoDocumentDataProvider('Answer', array('criteria' => $criteria));
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * delete an answer
     */
    public function actionDelete($id) {
        $model = $this->loadModel($id);
        $model->delete();
        Yii::app()->user->setFlash('success', 'The document has been deleted with success.');
        $dataProvider = new EMongoDocumentDataProvider('Answer');
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
        $model = Answer::model()->findByPk(new MongoID($id));
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
