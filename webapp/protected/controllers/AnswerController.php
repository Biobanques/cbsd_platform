<?php

class AnswerController extends Controller {

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
        if (!Yii::app()->user->isAuthorizedView($_SESSION['activeProfil'], $model->type)) {
            if ($model->type == "clinique" && Yii::app()->user->id != $model->login) {
                Yii::app()->user->setFlash('error', 'Vous n\'êtes pas autorisé à consulter une fiche clinique qui ne vous appartient pas');
            } else {
                Yii::app()->user->setFlash('error', 'Vous n\'êtes pas autorisé à consulter une fiche ' . $model->type);
            }
            $this->redirect(array('answer/affichepatient'));
        }
        if (isset($_SESSION['datapatient'])) {
            $patient = $_SESSION['datapatient'];
            $this->render('view', array(
                'model' => $model,
                'patient' => $patient,
            ));
        }
    }

    public function actionAffichepatient() {
        $model = new PatientForm;
        $patient = (object) null;
        if (isset($_SESSION['datapatient'])) {
            $patient = $_SESSION['datapatient'];
            $model->copyPatient($patient);
        }
        if (isset($_POST['PatientForm'])) {
            $actionForm = $_POST['PatientForm']['action'];
            $model = new PatientForm;
            $model->attributes = $_POST['PatientForm'];
            if ($_POST['PatientForm']['prenom'] == "" || $_POST['PatientForm']['nom_naissance'] == "" || $_POST['PatientForm']['date_naissance'] == "") {
                Yii::app()->user->setFlash(TbAlert::TYPE_ERROR, "Tous les champs ne sont pas remplis.");
                $this->redirect(array('site/patient'));
            }
            if ($model->dateFormat($model->date_naissance) == false) {
                Yii::app()->user->setFlash('error', "Entrez une date valide au format jj/mm/aaaa");
                $this->redirect(array('site/patient'));
            }

            $patient->id = null;
            $patient->source = null; //à identifier en fonction de l'app
            $patient->sourceId = null;
            $patient->birthName = $model->nom_naissance;
            if ($model->nom != "")
                $patient->useName = $model->nom;
            else
                $patient->useName = null;
            $patient->firstName = $model->prenom;
            $patient->birthDate = $model->date_naissance;
            if ($model->sexe != "") {
                $patient->sex = $model->sexe;
            } else {
                $patient->sex = null;
            }
            if ($actionForm == 'create') {
                $patient->source = (($model->source == "1") ? "1" : "2");
                $patientest = CommonTools::wsGetPatient($patient);
                if ($patientest == 'NoPatient') {
                    $patient = CommonTools::wsAddPatient($patient);
                } else {
                    $patient = $patientTest;
                }
            } else {
                $patient = CommonTools::wsGetPatient($patient);
            }
        }

        switch ($patient) {
            case "NoPatient":
                Yii::app()->user->setFlash(TbAlert::TYPE_ERROR, "Aucun patient avec ces informations n’existe dans le système, veuillez compléter le formulaire afin de créer le nouveau patient.");
                Yii::app()->user->setState('patientModel', $model);
                $model->scenario = 'create';
                $this->render('patient_bis', array('model' => $model, 'actionForm' => 'create'));
                exit();
                break;
            case "PatientNotSaved":
                Yii::app()->user->setFlash(TbAlert::TYPE_ERROR, "Le patient n'a pas pu être enregistré, merci de completer l'ensemble des champs");
                Yii::app()->user->setState('patientModel', $model);
                $model->scenario = 'create';
                $this->render('patient_bis', array('model' => $model, 'actionForm' => 'create'));
                exit();
                break;
            case "ManyPatient":
                Yii::app()->user->setFlash(TbAlert::TYPE_ERROR, "Plusieurs patients ont été trouvé dans le système, veuillez renseigner les champs supplémentaires.");
                Yii::app()->user->setState('patientModel', $model);
                $this->render('patient_bis', array('model' => $model, 'actionForm' => 'search'));
                exit();
                break;
            default:
        }
        if ($model->validate() && isset($patient->id)) {
            $model->id = $patient->id;
            $criteria = new EMongoCriteria();
            $criteria->id_patient = (string) $model->id;
            $criteriaCliniques = new EMongoCriteria($criteria);
            if (Yii::app()->user->getState('activeProfil') == "clinicien")
                $criteriaCliniques->login = Yii::app()->user->id;
            $criteriaCliniques->type = "clinique";
            $criteriaNeuropathologiques = new EMongoCriteria($criteria);
            $criteriaNeuropathologiques->type = "neuropathologique";
            $neuropath = Answer::model()->findAll($criteriaNeuropathologiques);
            $criteriaGenetiques = new EMongoCriteria($criteria);
            $criteriaGenetiques->type = "genetique";
            $genetique = Answer::model()->findAll($criteriaGenetiques);


            $dataProviderCliniques = new EMongoDocumentDataProvider('Answer');
            $dataProviderCliniques->setId('dpCli');
            $dataProviderNeuropathologiques = new EMongoDocumentDataProvider('Answer');
            $dataProviderCliniques->setId('dpNeuPa');
            $dataProviderGenetiques = new EMongoDocumentDataProvider('Answer');
            $dataProviderCliniques->setId('dpGen');
            $dataProviderCliniques->setCriteria($criteriaCliniques);
            $dataProviderNeuropathologiques->setCriteria($criteriaNeuropathologiques);
            $dataProviderGenetiques->setCriteria($criteriaGenetiques);


            $questionnaire = Questionnaire::model()->findAll();
            $_SESSION['datapatient'] = $patient;
            if (isset($_SESSION['datapatient'])) {
                $this->render('affichepatient', array('model' => $model, 'dataProviderCliniques' => $dataProviderCliniques, 'dataProviderNeuropathologiques' => $dataProviderNeuropathologiques, 'dataProviderGenetiques' => $dataProviderGenetiques, 'questionnaire' => $questionnaire, 'patient' => $patient, 'neuropath' => $neuropath, 'genetique' => $genetique));
            } elseif (isset($_POST['PatientForm'])) {
                $this->render('affichepatient', array('model' => $model, 'dataProviderCliniques' => $dataProviderCliniques, 'dataProviderNeuropathologiques' => $dataProviderNeuropathologiques, 'dataProviderGenetiques' => $dataProviderGenetiques, 'patient' => $patient));
            } else {
                $this->redirect(array('site/patient'));
            }
        } else {
            $this->redirect(array('site/patient'));
        }
    }

    /**
     * Display to update answers
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        if (!Yii::app()->user->isAuthorizedUpdate($_SESSION['activeProfil'], $model->type)) {
            Yii::app()->user->setFlash('error', 'Vous n\'êtes pas autorisé à modifier une fiche ' . $model->type);
            $this->redirect(array('answer/affichepatient'));
        }
        if (isset($_POST['Questionnaire'])) {
            $model->last_updated = new MongoDate();
            $flagNoInputToSave = true;
            foreach ($model->answers_group as $answer_group) {
                foreach ($answer_group->answers as $answerQuestion) {
                    $input = $answer_group->id . "_" . $answerQuestion->id;
                    if (isset($_POST['Questionnaire'][$input])) {
                        $flagNoInputToSave = false;
                        if ($answerQuestion->type != "number" && $answerQuestion->type != "expression") {
                            $answerQuestion->setAnswer($_POST['Questionnaire'][$input]);
                        } else {
                            $answerQuestion->setAnswerNumerique($_POST['Questionnaire'][$input]);
                        }
                    }
                }
            }
            if ($flagNoInputToSave == false) {
                if ($model->save()) {
                    Yii::app()->user->setFlash('success', "La fiche a bien été sauvegardée.");
                    $this->redirect(array('answer/affichepatient'));
                } else {
                    Yii::app()->user->setFlash('error', "La fiche n'a pas été sauvegardée. Un problème est apparu.");
                    Yii::log("pb save answer" . print_r($answer->getErrors()), CLogger::LEVEL_ERROR);
                }
            }
        }
        if (isset($_SESSION['datapatient'])) {
            $patient = $_SESSION['datapatient'];
        }
        $this->render('update', array(
            'model' => $model,
            'patient' => $patient,
        ));
    }

    public function actionUpdateAndAdd($id) {
        $model = $this->loadModel($id);
        $nbMax = 0;
        if (isset($_POST['Questionnaire'])) {
            $model->last_updated = new MongoDate();
            foreach ($model->answers_group as $answer_group) {
                foreach ($answer_group->answers as $answerQuestion) {
                    $nbMax = $model->getMaxIdGene($nbMax, $answerQuestion->id);
                    $input = $answer_group->id . "_" . $answerQuestion->id;
                    if (isset($_POST['Questionnaire'][$input])) {
                        $answerQuestion->setAnswer($_POST['Questionnaire'][$input]);
                    }
                }
            }

            $nbMax++;

            $gene = new AnswerQuestion;
            $model->addGene($nbMax, $gene);

            $analyse = new AnswerQuestion;
            $model->addAnalyse($nbMax, $analyse);

            $mutation = new AnswerQuestion;
            $model->addMutation($nbMax, $mutation);

            $comment = new AnswerQuestion;
            $model->addComment($nbMax, $comment);

            $model->addGeneToAnswers($model->answers_group, $gene, $analyse, $mutation, $comment);

            if ($model->save()) {
                Yii::app()->user->setFlash('success', "Le gène a bien été ajouté à la fiche.");
            } else {
                Yii::app()->user->setFlash('error', "La fiche n'a pas été sauvegardé. Un problème est apparu.");
                Yii::log("pb save answer" . print_r($answer->getErrors()), CLogger::LEVEL_ERROR);
            }
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
     * delete fiche
     */
    public function actionDelete($id) {
        $model = $this->loadModel($id);
        try {
            $model->delete();
            if (!isset($_GET['ajax'])) {
                Yii::app()->user->setFlash('success', 'La fiche a bien été supprimé.');
            } else {
                echo "<div class='alert in alert-block fade alert-success'>La fiche a bien été supprimé.</div>"; //for ajax
            }
        } catch (CDbException $e) {
            if (!isset($_GET['ajax'])) {
                Yii::app()->user->setFlash('error', "La fiche n'a pas été supprimé. Un problème est apparu.");
            } else {
                echo "<div class='alert in fade alert-error'>La fiche n'a pas été supprimé. Un problème est apparu.</div>";
            } //for ajax
        }
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
        AnswerPDFRenderer::renderAnswer($this->loadModel($id));
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
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
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

    public function actionAddSearchFilter() {
        if (isset($_POST['question']) && !empty($_POST['question'])) {
            $id = $_POST['question'];
            $question = Answer::model()->findAllDetailledQuestionById($id);
            echo QuestionnaireHTMLRenderer::renderQuestionForSearchHTML($question, 'fr', false);
        }
    }

}
