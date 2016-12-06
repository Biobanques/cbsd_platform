<?php

/**
 * controller de formulaire. Permet d aiguiller les actions sur l objet formulaire côté admin.
 * @author nmalservet
 */
class FormulaireController extends Controller {

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
        );
    }

    public function accessRules() {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('create', 'update', 'admin', 'view', 'delete', 'deleteQuestion', 'deleteQuestionGroup', 'dynamicquestions'),
                'expression' => '$user->getActiveProfil() == "administrateur"'
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * gestion des formulaires
     */
    public function actionAdmin() {
        $model = new Questionnaire('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Questionnaire']))
            $model->attributes = $_GET['Questionnaire'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Affiche un formulaire ,en  lecture uniquement
     * @param $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $model = Questionnaire::model()->findByPk(new MongoID($id));
        $this->render('view', array(
            'model' => $model,
        ));
    }

    /**
     * action de création d'un questionnaire.
     * Permet de saisir les éléments de base
     */
    public function actionCreate() {
        $model = new Questionnaire;
        $validate = false;
        if (isset($_POST['Questionnaire'])) {
            $model->attributes = $_POST['Questionnaire'];
            $model->creator = ucfirst(Yii::app()->user->getPrenom()) . " " . strtoupper(Yii::app()->user->getNom());
            $model->addQuestionGroup("firstgroup", "Questionnaire principal");
            $countIdForm = $model->getFormsById($model->id);
            $countNameForm = $model->getFormsByName($model->name);
            if (count($countIdForm) > 0) {
                Yii::app()->user->setFlash('error', Yii::t('common', 'idFormExist'));
            } elseif (count($countNameForm) > 0) {
                Yii::app()->user->setFlash('error', Yii::t('common', 'nameFormExist'));
            } else {
                $validate = true;
            }
            if ($validate) {
                if ($model->save()) {
                    Yii::app()->user->setFlash('success', Yii::t('common', 'formSaved'));
                    $this->redirect($this->createUrl('update', array('id' => $model->_id)));
                } else {
                    Yii::app()->user->setFlash('error', Yii::t('common', 'missingFields'));
                }
            }
        }
        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Mise  àjour d un formulaire /mode edition
     * @param $id the ID of the model to be displayed
     */
    public function actionUpdate($id) {
        $model = Questionnaire::model()->findByPk(new MongoID($id));
        $questionBlocForm = new QuestionBlocForm();
        $questionForm = new QuestionForm;
        $questionGroup = new QuestionGroup;
        // collect user input data
        if (isset($_POST['QuestionForm'])) {
            $questionForm->attributes = $_POST['QuestionForm'];
            if ($questionForm->validatewithId($model)) {
                if ($questionForm->help == "") {
                    $questionForm->help = null;
                }
                //traitement ajout de question
                if ($questionForm->validate()) {
                    $model = $model->saveQuestionnaireNewQuestion($questionForm);
                } else {
                    Yii::app()->user->setFlash('error', Yii::t('common', 'questionNotAdded'));
                }
            } else {
                Yii::app()->user->setFlash('error', Yii::t('common', 'questionNotAdded'));
            }
        }
        if (isset($_POST['QuestionGroup'])) {
            $questionGroup->attributes = $_POST['QuestionGroup'];
            if ($questionGroup->validatewithId($model)) {
                //copie du titre sur l option fr
                $questionGroup->title_fr = $questionGroup->title;
                if ($questionGroup->validate()) {
                    $model = $model->saveQuestionnaireNewGroup($questionGroup);
                }
            } else {
                Yii::app()->user->setFlash('error', Yii::t('common', 'tabNotAdded'));
            }
        }

        if (isset($_POST['QuestionBlocForm'])) {
            $questionBlocForm->attributes = $_POST['QuestionBlocForm'];
            if ($questionBlocForm->validatewithId($model)) {
                $questionBloc = QuestionBloc::model()->findByPk(new MongoId($questionBlocForm->title));
                $questionBlocForm->title_fr = $questionBlocForm->title;
                $computedGroup = new QuestionGroup;
                $computedGroup->copy($questionBlocForm, $questionBloc);

                if ($computedGroup->validate())
                    $model = $model->saveQuestionnaireNewGroup($computedGroup);

                if (isset($questionBloc->questions) && ($questionBloc->questions != null) && (count($questionBloc->questions) > 0)) {
                    foreach ($questionBloc->questions as $question => $value) {
                        $currentQuestion = Question::model()->findByPk(new MongoId($value));

                        $questionForm->copy($currentQuestion, $computedGroup);

                        $model->saveQuestionnaireNewQuestionBloc($questionForm);
                    }
                }
            } else {
                Yii::app()->user->setFlash('error', Yii::t('common', 'tabNotAdded'));
            }
        }
        if (isset($_POST['old_onglet'])) {
            foreach ($model->questions_group as $onglet) {
                if ($onglet->id == $_POST['old_onglet']) {
                    $onglet->title = $_POST['new_onglet'];
                    $onglet->title_fr = $_POST['new_onglet'];
                    if ($model->save()) {
                        Yii::app()->user->setFlash('success', Yii::t('common', 'tabUpdated'));
                    } else {
                        Yii::app()->user->setFlash('error', Yii::t('common', 'tabNotUpdated'));
                    }
                }
            }
        }
        if (isset($_POST['old_question'])) {
            foreach ($model->questions_group as $onglet) {
                foreach ($onglet->questions as $question) {
                    if ($question->id == $_POST['old_question']) {
                        $question->label = $_POST['new_question'];
                        $question->label_fr = $_POST['new_question'];
                        if ($model->save()) {
                            Yii::app()->user->setFlash('success', Yii::t('common', 'questionUpdated'));
                        } else {
                            Yii::app()->user->setFlash('error', Yii::t('common', 'questionNotUpdated'));
                        }
                    }
                }
            }
        }
        //  set du model sur la questionForm pour generer l arborescende de position de question
        $questionForm->questionnaire = $model;
        $questionGroup->questionnaire = $model;
        $this->render('update', array(
            'model' => $model,
            'questionForm' => $questionForm,
            'questionGroup' => $questionGroup,
            'questionBloc' => $questionBlocForm
        ));
    }

    /**
     * Delete un formulaire
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $model = Questionnaire::model()->findByPk(new MongoID($id));
        try {
            $model->delete();
            if (!isset($_GET['ajax'])) {
                Yii::app()->user->setFlash('success', Yii::t('common', 'formDeleted'));
            } else {
                echo "<div class='alert in alert-block fade alert-success'>" . Yii::t('common', 'formDeleted') . "</div>"; //for ajax
            }
        } catch (CDbException $e) {
            if (!isset($_GET['ajax'])) {
                Yii::app()->user->setFlash('error', Yii::t('common', 'formNotDeleted'));
            } else {
                echo "<div class='alert in fade alert-error'>" . Yii::t('common', 'formNotDeleted') . "</div>";
            } //for ajax
        }
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Supprime une question du formulaire
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDeleteQuestion($idFormulaire, $idQuestion) {
        $questionBloc = new QuestionBloc;
        $model = Questionnaire::model()->findByPk(new MongoID($idFormulaire));
        if ($model->deleteQuestion($idQuestion)) {
            if ($model->save()) {
                Yii::app()->user->setFlash('success', Yii::t('common', 'questionDeleted'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('common', 'questionNotDeleted'));
                Yii::log("pb save delete question", CLogger::LEVEL_ERROR);
            }
        }
        //go back on update mode

        $this->redirect($this->createUrl('update', array('id' => $model->_id)));
    }

    /**
     * Supprime un groupe de questions du formulaire
     * @param $idFormulaire
     * @param $idQuestionGroup
     */
    public function actionDeleteQuestionGroup($idFormulaire, $idQuestionGroup) {
        $model = Questionnaire::model()->findByPk(new MongoID($idFormulaire));
        if ($model->deleteQuestionGroup($idQuestionGroup)) {
            if ($model->save()) {
                Yii::app()->user->setFlash('success', Yii::t('common', 'tabDeleted'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('common', 'tabNotDeleted'));
                Yii::log("pb save delete question", CLogger::LEVEL_ERROR);
            }
        }
        //go back on update mode
        $this->redirect($this->createUrl('update', array('id' => $model->_id)));
    }

    /**
     * save a new question into the questionnaire
     * si pas de positionnement on ajoute la questionen au debut du  groupe
     * @param questionnaire
     */
    public function saveQuestionnaireNewQuestion($questionnaire, $questionForm) {
        $questionnaire->last_modified = DateTime::createFromFormat('d/m/Y', date('d/m/Y'));
        $cquestion = new Question;
        $cquestion->setAttributesByQuestionForm($questionForm);
        Yii::log("save questionnaire", CLogger::LEVEL_TRACE);
        //si pas de position fournie, on ajoute la question a la fin, dans le premier groupe de question
        if (!isset($questionForm->idQuestionBefore) || empty($questionForm->idQuestionBefore)) {
            if ($questionnaire->questions_group != null && count($questionnaire->questions_group) > 0) {
                foreach ($questionnaire->questions_group as $group) {
                    if ($group->id == $questionForm->idQuestionGroup) {
                        if ($group->questions == null) {
                            $group->questions = array();
                            $group->questions[] = $cquestion;
                        } else {
                            array_unshift($group->questions, $cquestion);
                        }
                    }
                }
            }
        } else {
            //sinon positionnement relatif
            if ($questionnaire->questions_group != null) {
                foreach ($questionnaire->questions_group as $group) {
                    if ($group->questions != null) {
                        foreach ($group->questions as $key => $question) {
                            if ($question->id == $questionForm->idQuestionBefore) {
                                array_splice($group->questions, ($key + 1), 0, array($cquestion));
                            }
                        }
                    }
                }
            }
        }
        if ($questionnaire->save())
            Yii::app()->user->setFlash('success', Yii::t('common', 'questionDeleted'));
        else {
            Yii::app()->user->setFlash('error', Yii::t('common', 'questionNotDeleted'));
            Yii::log("pb save answer" . print_r($answer->getErrors()), CLogger::LEVEL_ERROR);
        }
        return $questionnaire;
    }

    /**
     * action pour afficher dynamiquement la liste de questions dans le formulaire
     * d ajout de question pour gerer le positionnement
     * @param id is questionnaire id
     */
    public function actionDynamicquestions($id) {
        Yii::log("dynamic question", CLogger::LEVEL_TRACE);
        $questionForm = new QuestionForm;
        $questionForm->attributes = $_POST['QuestionForm'];
        $questionnaire = Questionnaire::model()->findByPk(new MongoID($id));
        $data = $questionnaire->getArrayQuestions($questionForm->idQuestionGroup);
        Yii::log("count questions:" . count($data), CLogger::LEVEL_TRACE);
        //add the empty option si l on veut mettre la question au debut
        echo CHtml::tag('option', array('value' => ''), CHtml::encode("----"), true);
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }

    /**
     * save a new group into the questionnaire
     * si pas de positionnement on ajoute la questionen fin du premier groupe
     * @param questionnaire
     */
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
        if ($questionnaire->save())
            Yii::app()->user->setFlash('success', Yii::t('common', 'tabDeleted'));
        else {
            Yii::app()->user->setFlash('error', Yii::t('common', 'tabNotDeleted'));
            Yii::log("pb save answer" . print_r($answer->getErrors()), CLogger::LEVEL_ERROR);
        }
        return $questionnaire;
    }

}