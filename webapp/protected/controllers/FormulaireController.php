<?php

/**
 * controller de formulaire. Permet d aiguiller les actions sur l objet formulaire côté admin.
 * @author nmalservet
 */
class FormulaireController extends Controller
{
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
                'expression' => '$user->isAuthorized(Yii::app()->user->id, Yii::app()->controller->id)'
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
        if (isset($_POST['Questionnaire'])) {
            $model->attributes = $_POST['Questionnaire'];
            $model->addQuestionGroup("firstgroup", "Questionnaire principal");
            if ($model->save()) {
                Yii::app()->user->setFlash('success', 'Le questionnaire a été enregistré avec succès.');
                $this->redirect($this->createUrl('update', array('id' => $model->_id)));
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
            //traitement ajout de question
            if ($questionForm->validate()) {
                $model = $model->saveQuestionnaireNewQuestion($questionForm);
//                $model = $this->saveQuestionnaireNewQuestion($model, $questionForm);
            }
        }
        if (isset($_POST['QuestionGroup'])) {
            $questionGroup->attributes = $_POST['QuestionGroup'];
            //copie du titre sur l option fr
            $questionGroup->title_fr = $questionGroup->title;
            if ($questionGroup->validate()) {
                $model = $model->saveQuestionnaireNewGroup($questionGroup);
//                $model = $this->saveQuestionnaireNewGroup($model, $questionGroup);
            }
        }

        if (isset($_POST['QuestionBlocForm'])) {
//            $questionBloc = QuestionBloc::model()->findByPk(new MongoId($_POST['QuestionBloc']['_id']));
            $questionBlocForm->attributes = $_POST['QuestionBlocForm'];
            $questionBloc = QuestionBloc::model()->findByPk(new MongoId($questionBlocForm->title));
            //$bloc= QuestionBloc::model()->findByPk(new MongoId($questionBloc))
            $questionBlocForm->title_fr = $questionBlocForm->title;
            $computedGroup = new QuestionGroup;
            $computedGroup->id = $questionBlocForm->id;
            $computedGroup->title = $questionBloc->title;
            $computedGroup->title_fr = $questionBloc->title_fr;
            $computedGroup->parent_group = $questionBlocForm->parent_group;
            $computedGroup->questions = array();

            if ($computedGroup->validate())
                $model = $model->saveQuestionnaireNewGroup($computedGroup);
//                $model = $this->saveQuestionnaireNewGroup($model, $computedGroup);

            if (isset($questionBloc->questions) && ($questionBloc->questions != null) && (count($questionBloc->questions) > 0)) {
                foreach ($questionBloc->questions as $question => $value) {
                    //echo $value . "<br />";
                    $currentQuestion = Question::model()->findByPk(new MongoId($value));

                    $questionForm->label = $currentQuestion->label;
                    $questionForm->type = $currentQuestion->type;
                    $questionForm->style = $currentQuestion->style;
                    $questionForm->values = $currentQuestion->values;
                    $questionForm->id = $currentQuestion->id;
                    $questionForm->idQuestionGroup = $computedGroup->id;

                    $model->saveQuestionnaireNewQuestion($questionForm);
//                    $this->saveQuestionnaireNewQuestion($model, $questionForm);
                }
            }
        }
        //  set du model sur la questionForm pour generer l arborescende de position de question
        $questionForm->questionnaire = $model;
        $questionGroup->questionnaire = $model;
        //  $questionBloc->questionnaire = $model;
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
        $model->delete();
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
                Yii::app()->user->setFlash('success', "Formulaire enregistré avec sucès");
            } else {
                Yii::app()->user->setFlash('error', "Formulaire non enregistré. Un problème est apparu.");
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
                Yii::app()->user->setFlash('success', "Formulaire enregistré avec sucès");
            } else {
                Yii::app()->user->setFlash('error', "Formulaire non enregistré. Un problème est apparu.");
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
        $questionnaire->last_modified = new MongoDate();
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
            Yii::app()->user->setFlash('success', "Formulaire enregistré avec sucès");
        else {
            Yii::app()->user->setFlash('error', "Formulaire non enregistré. Un problème est apparu.");
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
        //$idQuestionGroup = $_POST['QuestionForm'];
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
        if ($questionnaire->save())
            Yii::app()->user->setFlash('success', "Formulaire enregistré avec sucès");
        else {
            Yii::app()->user->setFlash('error', "Formulaire non enregistré. Un problème est apparu.");
            Yii::log("pb save answer" . print_r($answer->getErrors()), CLogger::LEVEL_ERROR);
        }
        return $questionnaire;
    }

//    public function saveQuestionnaireNewBloc($questionnaire, $questionBloc) {
//        $questionBloc->id = $questionnaire->id;
//        if ($questionBloc != null) {
//            //sinon positionnement relatif
//            if ($questionnaire->questions_group != null) {
//                $questionnaire->questions_group[] = $questionBloc;
//            } else {
//                $questionnaire->questions_group = array();
//                $questionnaire->questions_group[] = $questionBloc;
//            }
//        }
//        if ($questionnaire->save())
//            Yii::app()->user->setFlash('success', "Formulaire enregistré avec sucès");
//        else {
//            Yii::app()->user->setFlash('error', "Formulaire non enregistré. Un problème est apparu.");
//            Yii::log("pb save answer" . print_r($answer->getErrors()), CLogger::LEVEL_ERROR);
//        }
//        return $questionnaire;
//    }
}