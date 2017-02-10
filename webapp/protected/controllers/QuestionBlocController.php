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
                'actions' => array('create', 'index', 'view', 'update', 'admin', 'delete', 'deleteQuestion'),
                'expression' => '$user->getActiveProfil() == "administrateur"'
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
        $questionnaire = new Questionnaire;
        $questionGroup = new QuestionGroup;

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
        $this->saveQuestionnaireNewGroup($questionnaire, $questionGroup);
        $this->render('view', array(
            'model' => $model,
            'questionnaire' => $questionnaire
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
            $countBloc = $model->getBlocsByTitle($model->title_fr);
            if (count($countBloc) > 0) {
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'titleExist'));
            } elseif ($model->save()) {
                Yii::app()->user->setFlash('succès', Yii::t('common', 'questionBlockSaved'));
                $this->redirect($this->createUrl('update', array('id' => $model->_id)));
            } else {
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'missingFields'));
            }
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
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'missingFields'));
        }

        $questionGroup->id = $model->title;
        $questionGroup->title = $model->title;
        $questionGroup->title_fr = $questionGroup->title;
        $questionGroup->questions = array();
        if (isset($model->questions) && ($model->questions != null) && (count($model->questions) > 0)) {
            foreach ($model->questions as $question => $value) {
                $currentQuestion = Question::model()->findByPk(new MongoId($value));
                $currentQuestion->label_fr = $currentQuestion->label;
                $currentQuestion->precomment_fr = $currentQuestion->precomment;
                $questionGroup->questions[] = $currentQuestion;
            }
        }
        $this->saveQuestionnaireNewGroup($questionnaire, $questionGroup);

        $this->render('update', array(
            'model' => $model,
            'questionForm' => $questionForm,
            'questionnaire' => $questionnaire
        ));
    }

    public function actionDeleteQuestion($id, $idQuestion) {

        $blocQuestionId = QuestionBloc::model()->findByPk(new MongoId($id));
        if (isset($blocQuestionId->questions) && in_array($idQuestion, $blocQuestionId->questions)) {
            unset($blocQuestionId->questions[array_search($idQuestion, $blocQuestionId->questions)]);
            $blocQuestionId->save();
        }
        $criteriaQuestion = new EMongoCriteria;
        $criteriaQuestion->_id = new MongoId($idQuestion);
        $question = Question::model()->find($criteriaQuestion);
        if ($question->delete()) {
            Yii::app()->user->setFlash('succès', Yii::t('common', 'questionDeleted'));
        } else {
            Yii::app()->user->setFlash('erreur', Yii::t('common', 'questionNotDeleted'));
            Yii::log("pb save delete question", CLogger::LEVEL_ERROR);
        }
        $this->redirect('index.php?r=questionBloc/update&id=' . $id);
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        try {
            $model = $this->loadModel($id);
            if (isset($model->questions)) {
                foreach ($model->questions as $value) {
                    Question::model()->findByPk(new MongoId($value))->delete();
                }
            }
            $this->loadModel($id)->delete();
            if (!isset($_GET['ajax'])) {
                Yii::app()->user->setFlash('succès', Yii::t('common', 'questionBlockDeleted'));
            } else {
                echo "<div class='flash-success'>" . Yii::t('common', 'questionBlockDeleted') . "</div>"; //for ajax
            }
        } catch (CDbException $e) {
            if (!isset($_GET['ajax'])) {
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'questionBlockNotDeleted'));
            } else {
                echo "<div class='flash-error'>" . Yii::t('common', 'questionBlockNotDeleted') . "</div>";
            } //for ajax
        }

// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
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
        var_dump($bloc);
        $cquestion = new Question;
        $cquestion->setAttributesByQuestionForm($questionForm);
        $bloc->questions = $questionForm->id;
        if ($bloc->save())
            Yii::app()->user->setFlash('succès', Yii::t('common', 'questionBlockSaved'));
        else {
            Yii::app()->user->setFlash('erreur', Yii::t('common', 'questionBlockNotSaved'));
        }
        return $bloc;
    }

    public function saveQuestionnaireNewGroup($questionnaire, $questionGroup) {
        $questionnaire->last_modified = DateTime::createFromFormat('d/m/Y', date('d/m/Y'));
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