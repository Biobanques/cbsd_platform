<?php

class QuestionBlocController extends Controller {

    /**
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
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'index', 'view', 'update', 'admin', 'delete', 'deleteQuestion', 'preview'),
                'expression' => '$user->isAdmin()'
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
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new QuestionBloc;

        if (isset($_POST['QuestionBloc'])) {
            $model->attributes = $_POST['QuestionBloc'];
            $model->title_fr = $model->title;
            if ($model->save())
            //$this->redirect($this->createUrl('update', array('id' => $model->_id)));
                $this->redirect($this->createUrl('admin'));
            else
                Yii::app()->user->setFlash('error', "Veuillez renseigner tous les champs obligatoires.");
        }

        $this->render('create', array(
            'model' => $model
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        $questionForm = new QuestionForm;
        $questionModel = new Question;
        $questionnaire = new Questionnaire;
        $questionGroup = new QuestionGroup;   

        if (isset($_POST['QuestionBloc'])) {
            $model->attributes = $_POST['QuestionBloc'];
        }

        if (isset($_POST['QuestionForm'])) {
            $questionModel->attributes = $_POST['QuestionForm'];
            if ($questionModel->save()) {
                $idQuestion = (string) $questionModel->_id;
                $model->questions[] = $idQuestion;
            }

            if ($model->save())
                $this->redirect($this->createUrl('update', array('id' => $model->_id)));
            else
                Yii::app()->user->setFlash('error', "Veuillez renseigner tous les champs obligatoires.");
        }

        $questionGroup->id = $model->title;
        $questionGroup->title = $model->title;
        $questionGroup->title_fr = $questionGroup->title;
        $questionGroup->questions = array();
        if (isset($model->questions) && ($model->questions != null) && (count($model->questions) > 0)) {
            foreach ($model->questions as $question => $value) {
                $currentQuestion = Question::model()->findByPk(new MongoId($value));
                $currentQuestion->label_fr = $currentQuestion->label;
                $questionGroup->questions[] = $currentQuestion;
            }
        }
        $this->saveQuestionnaireNewGroup($questionnaire,$questionGroup);

        $this->render('update', array(
            'model' => $model,
            'questionForm' => $questionForm,
            'questionnaire' => $questionnaire
        ));
    }

    public function actionDeleteQuestion($id, $blocId) {
        $model = $this->loadModel($blocId);
        $model->questions = array_diff($model->questions, array($id));
        if ($model->save())
            Yii::app()->user->setFlash('success', "La question a bien été supprimée");
        else
            Yii::app()->user->setFlash('error', "La question n'a pas pu être supprimée.");
        $this->redirect($this->createUrl('update', array('id' => $model->_id)));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new EMongoDocumentDataProvider('QuestionBloc');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new QuestionBloc('search');
        $model->unsetAttributes();

        if (isset($_GET['QuestionBloc']))
            $model->setAttributes($_GET['QuestionBloc']);

        $this->render('admin', array(
            'model' => $model
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = QuestionBloc::model()->findByPk(new MongoId($id));
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'question-bloc-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function saveBlocNewQuestion($bloc, $questionForm) {
        $cquestion = new Question;
        $cquestion->setAttributesByQuestionForm($questionForm);
        $bloc->questions = $questionForm->id;
        if ($bloc->save())
            Yii::app()->user->setFlash('success', "Bloc enregistré avec succès");
        else {
            Yii::app()->user->setFlash('error', "Bloc non enregistré. Un problème est apparu.");
        }
        return $bloc;
    }

    public function saveQuestionnaireNewGroup($questionnaire, $questionGroup) {
        $questionnaire->last_modified = new MongoDate();
        if ($questionGroup != null) {

            //sinon positionnement relatif
            if ($questionnaire->questions_group != null) {
                $questionnaire->questions_group[] = $questionGroup;
            } else {
                $questionnaire->questions_group = array();
                $questionnaire->questions_group[] = $questionGroup;
            }
        }
        return $questionnaire;
    }

}
