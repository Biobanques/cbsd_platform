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
                'expression' => '$user->getActiveProfil() == "Administrateur"'
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
        if (!isset($_GET['ajax'])) {
            if (isset($_SESSION['checkedIds'])) {
                foreach ($_SESSION['checkedIds'] as $ar) {
                    Yii::app()->user->setState($ar, 0);
                }
            }
        }
        if (isset($_GET['Questionnaire'])) {
            $model->attributes = $_GET['Questionnaire'];
        }
        if (isset($_GET['checkedIds']) && !empty($_GET['checkedIds'])) {
            CommonTools::chkIds($_GET['checkedIds']);
        }
        if (isset($_GET['uncheckedIds']) && !empty($_GET['uncheckedIds'])) {
            CommonTools::unckIds($_GET['uncheckedIds']);
        }
        if (isset($_POST['rechercher'])) {
            if (isset($_POST['Form_id'])) {
                if (isset($_SESSION['checkedIds'])) {
                    foreach ($_SESSION['checkedIds'] as $user_id) {
                        array_push($_POST['Form_id'], $user_id);
                    }
                }
                foreach ($_POST['Form_id'] as $key => $value) {
                    if ($this->loadModel($value) !== null) {
                        $this->loadModel($value)->delete();
                    }
                    Yii::app()->user->setFlash('succès', Yii::t('form', 'formsDeleted'));
                }
            } else {
                Yii::app()->user->setFlash('erreur', Yii::t('form', 'formsNotDeleted'));
            }
        }

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
            $model->id = str_replace(' ', '_', strtolower($model->name));
            //$model->addQuestionGroup("firstgroup", "Questionnaire principal");
            $this->addQuestionGroupSituation($model);
            $countIdForm = $model->getFormsById($model->id);
            $countNameForm = $model->getFormsByName($model->name);
            if (count($countIdForm) > 0) {
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'idFormExist'));
            } elseif (count($countNameForm) > 0) {
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'nameFormExist'));
            } else {
                $validate = true;
            }
            if ($validate) {
                if ($model->save()) {
                    Yii::app()->user->setFlash('succès', Yii::t('common', 'formSaved'));
                    $this->redirect($this->createUrl('update', array('id' => $model->_id)));
                } else {
                    Yii::app()->user->setFlash('erreur', Yii::t('common', 'missingFields'));
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
                $questionForm->id = str_replace(' ', '', trim($questionForm->id));
                $questionForm->values = str_replace(', ', ',', trim($questionForm->values));
                $questionForm->label = ucfirst($questionForm->label);
                $questionForm->values = CommonTools::ucwords_all($questionForm->values);
                if ($questionForm->validate()) {
                    $model = $model->saveQuestionnaireNewQuestion($questionForm);
                    $questionForm->unsetAttributes();
                } else {
                    Yii::app()->user->setFlash('erreur', Yii::t('common', 'questionNotAdded'));
                }
            } else {
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'questionNotAdded'));
            }
        }
        if (isset($_POST['QuestionGroup'])) {
            $questionGroup->attributes = $_POST['QuestionGroup'];
            if ($questionGroup->validatewithId($model)) {
                //copie du titre sur l option fr
                $questionGroup->id = str_replace(' ', '', trim($questionGroup->id));
                $questionGroup->title_fr = $questionGroup->title;
                if ($questionGroup->validate()) {
                    $model = $model->saveQuestionnaireNewGroup($questionGroup);
                }
            } else {
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'tabNotAdded'));
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
                        $questionForm->unsetAttributes();
                    }
                }
            } else {
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'tabNotAdded'));
            }
        }
        if (isset($_POST['old_onglet'])) {
            foreach ($model->questions_group as $onglet) {
                if ($onglet->id == $_POST['old_onglet']) {
                    $onglet->title = $_POST['new_onglet'];
                    $onglet->title_fr = $_POST['new_onglet'];
                    if ($model->save()) {
                        Yii::app()->user->setFlash('succès', Yii::t('common', 'tabUpdated'));
                    } else {
                        Yii::app()->user->setFlash('erreur', Yii::t('common', 'tabNotUpdated'));
                    }
                }
            }
        }
        if (isset($_POST['updateForm'])) {
            if (isset($_POST['old_name'])) {
                if ($_POST['new_name'] != "") {
                    $model->name = ucfirst($_POST['new_name']);
                } else {
                    $model->name = $_POST['old_name'];
                }
                $model->description = $_POST['new_description'];
                if ($model->save()) {
                    Yii::app()->user->setFlash('succès', Yii::t('common', 'nameFormUpdated'));
                } else {
                    Yii::app()->user->setFlash('erreur', Yii::t('common', 'nameFormNotUpdated'));
                }
            }
            if (isset($_POST['old_question'])) {
                foreach ($model->questions_group as $onglet) {
                    foreach ($onglet->questions as $question) {
                        if ($question->id == $_POST['old_question']) {
                            if (isset($_POST['new_question']) && $_POST['new_question'] != "") {
                                $question->label = $_POST['new_question'];
                                $question->label_fr = $_POST['new_question'];
                            }
                            if (isset($_POST['new_type']) && $_POST['new_type'] != "") {
                                $question->type = $_POST['new_type'];
                                if ($question->type != "radio" || $question->type != "list" || $question->type != "checkbox") {
                                    $question->values = "";
                                }
                            }
                            if (isset($_POST['new_values']) && $_POST['new_values'] != "") {
                                $question->values = str_replace(', ', ',', trim($_POST['new_values']));
                                $question->values = CommonTools::ucwords_all($question->values);
                            }
                            if (isset($_POST['new_help']) && $_POST['new_help'] != "") {
                                $question->help = $_POST['new_help'];
                            }
                            if (isset($_POST['new_defaultValue']) && $_POST['new_defaultValue'] != "") {
                                $question->defaultValue = $_POST['new_defaultValue'];
                            }
                            if ($model->save()) {
                                Yii::app()->user->setFlash('succès', Yii::t('common', 'questionUpdated'));
                            } else {
                                Yii::app()->user->setFlash('erreur', Yii::t('common', 'questionNotUpdated'));
                            }
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

    public function loadModel($id) {
        $model = Questionnaire::model()->findByPk(new MongoId($id));
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
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
                Yii::app()->user->setFlash('succès', Yii::t('common', 'formDeleted'));
            } else {
                echo "<div class='flash-success'>" . Yii::t('common', 'formDeleted') . "</div>"; //for ajax
            }
        } catch (CDbException $e) {
            if (!isset($_GET['ajax'])) {
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'formNotDeleted'));
            } else {
                echo "<div class='flash-error'>" . Yii::t('common', 'formNotDeleted') . "</div>";
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
        $model = Questionnaire::model()->findByPk(new MongoID($idFormulaire));
        if ($model->deleteQuestion($idQuestion)) {
            if ($model->save()) {
                Yii::app()->user->setFlash('succès', Yii::t('common', 'questionDeleted'));
            } else {
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'questionNotDeleted'));
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
                Yii::app()->user->setFlash('succès', Yii::t('common', 'tabDeleted'));
            } else {
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'tabNotDeleted'));
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
        $questionnaire->last_modified = DateTime::createFromFormat(CommonTools::FRENCH_SHORT_DATE_FORMAT, date(CommonTools::FRENCH_SHORT_DATE_FORMAT));
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
            Yii::app()->user->setFlash('succès', Yii::t('common', 'questionDeleted'));
        else {
            Yii::app()->user->setFlash('erreur', Yii::t('common', 'questionNotDeleted'));
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
        $questionnaire->last_modified = DateTime::createFromFormat(CommonTools::FRENCH_SHORT_DATE_FORMAT, date(CommonTools::FRENCH_SHORT_DATE_FORMAT));
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
            Yii::app()->user->setFlash('succès', Yii::t('common', 'tabDeleted'));
        else {
            Yii::app()->user->setFlash('erreur', Yii::t('common', 'tabNotDeleted'));
            Yii::log("pb save answer" . print_r($answer->getErrors()), CLogger::LEVEL_ERROR);
        }
        return $questionnaire;
    }

    public function addQuestionGroupSituation($questionnaire) {
        $qg = new QuestionGroup;
        $qg->id = 'situation';
        $qg->title = 'Renseignements individuels';
        $qg->title_fr = $qg->title;
        $questionnaire->questions_group[] = $qg;
        $q1 = new Question;
        $q1->id = 'patientaddress';
        $q1->label = 'Adresse du patient';
        $q1->label_fr = $q1->label;
        $q1->type = 'input';
        $q1->style = '';
        $q1->values = '';
        $q1->values_fr = '';
        $q1->defaultValue = null;
        $q1->help = null;
        $q1->precomment = '';
        $q1->precomment_fr = '';
        $qg->questions[] = $q1;
        $q2 = new Question;
        $q2->id = 'patientage';
        $q2->label = 'Age du patient';
        $q2->label_fr = $q2->label;
        $q2->type = 'number';
        $q2->style = 'float:right';
        $q2->values = '';
        $q2->values_fr = '';
        $q2->defaultValue = null;
        $q2->help = null;
        $q2->precomment = '';
        $q2->precomment_fr = '';
        $qg->questions[] = $q2;
        $q3 = new Question;
        $q3->id = 'doctorname';
        $q3->label = 'Nom du médecin';
        $q3->label_fr = $q3->label;
        $q3->type = 'input';
        $q3->style = '';
        $q3->values = '';
        $q3->values_fr = '';
        $q3->defaultValue = null;
        $q3->help = null;
        $q3->precomment = '';
        $q3->precomment_fr = '';
        $qg->questions[] = $q3;
        $q4 = new Question;
        $q4->id = 'examdate';
        $q4->label = 'Date de l\'examen';
        $q4->label_fr = $q1->label;
        $q4->type = 'date';
        $q4->style = 'float:right';
        $q4->values = '';
        $q4->values_fr = '';
        $q4->defaultValue = null;
        $q4->help = null;
        $q4->precomment = '';
        $q4->precomment_fr = '';
        $qg->questions[] = $q4;
        if ($questionnaire->type == "neuropathologique") {
            $q5 = new Question;
            $q5->id = 'deathdate';
            $q5->label = 'Date de décès';
            $q5->label_fr = $q5->label;
            $q5->type = 'date';
            $q5->style = '';
            $q5->values = '';
            $q5->values_fr = '';
            $q5->defaultValue = null;
            $q5->help = null;
            $q5->precomment = '';
            $q5->precomment_fr = '';
            $qg->questions[] = $q5;
        }
    }

}
