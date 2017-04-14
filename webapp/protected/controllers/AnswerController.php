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
            array(
                'allow',
                'actions' => array(
                    'view',
                    'affichepatient',
                    'update',
                    'updateAndAdd',
                    'index',
                    'delete',
                    'viewOnePage',
                    'exportPDF',
                    'addSearchFilter',
                    'addSearchReplaceFilter',
                    'writeQueries',
                    'exportQueries'
                ),
                'expression' => '$user->getActiveProfil() != "chercheur"'
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
                Yii::app()->user->setFlash("erreur", Yii::t('common', 'missingFields'));
                $this->redirect(array('site/patient'));
            }
            if (CommonTools::isDate($model->date_naissance) == false) {
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'unvalidDate'));
                $this->redirect(array('site/patient'));
            }

            $patient->id = null;
            $patient->source = null; //à identifier en fonction de l'app
            $patient->sourceId = null;
            $patient->birthName = $model->nom_naissance;
            if ($model->nom != "") {
                $patient->useName = $model->nom;
            } else if ($model->nom == "" && $actionForm == 'create') {
                $patient->useName = $model->nom_naissance;
            } else {
                $patient->useName = null;
            }
            $patient->firstName = $model->prenom;
            $patient->birthDate = $model->date_naissance;
            if ($model->sexe != "") {
                $patient->sex = $model->sexe;
            } else {
                $patient->sex = null;
            }
            if ($actionForm == 'create') {
                $patient->source = 1;
                $patientest = CommonTools::wsGetPatient($patient);
                if ($patientest == 'NoPatient') {
                    $patient = CommonTools::wsAddPatient($patient);
                }
            } else {
                $patient = CommonTools::wsGetPatient($patient);
            }
        }

        switch ($patient) {
            case "NoPatient":
                Yii::app()->user->setFlash("erreur", Yii::t('common', 'noPatient'));
                Yii::app()->user->setState('patientModel', $model);
                $model->scenario = 'create';
                $this->render('patient_bis', array('model' => $model, 'actionForm' => 'create'));
                exit();
                break;
            case "PatientNotSaved":
                Yii::app()->user->setFlash("erreur", Yii::t('common', 'patientNotSaved'));
                Yii::app()->user->setState('patientModel', $model);
                $model->scenario = 'create';
                $this->render('patient_bis', array('model' => $model, 'actionForm' => 'create'));
                exit();
                break;
            case "ManyPatient":
                Yii::app()->user->setFlash("erreur", Yii::t('common', 'manyPatient'));
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
            if (Yii::app()->user->getState('activeProfil') == "Clinicien") {
                $criteriaCliniques->login = Yii::app()->user->id;
            }
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
        if (Yii::app()->user->id != $model->login) {
            switch ($model->type) {
                case "clinique" :
                    Yii::app()->user->setFlash("erreur", Yii::t('common', 'notAllowUpdateClinicalPatientForm'));
                    break;
                case "neuropathologique" :
                    Yii::app()->user->setFlash("erreur", Yii::t('common', 'notAllowUpdateNeuropathologicalPatientForm'));
                    break;
                case "genetique" :
                    Yii::app()->user->setFlash("erreur", Yii::t('common', 'notAllowUpdateGeneticPatientForm'));
                    break;
            }
            $this->redirect(array('answer/affichepatient'));
        }
        if (isset($_POST['Questionnaire'])) {
            $model->last_updated = DateTime::createFromFormat(CommonTools::FRENCH_SHORT_DATE_FORMAT, date(CommonTools::FRENCH_SHORT_DATE_FORMAT));
            $flagNoInputToSave = true;
            foreach ($model->answers_group as $answer_group) {
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
                }
            }
            if ($flagNoInputToSave == false) {
                if ($model->save()) {
                    Yii::app()->user->setFlash('succès', Yii::t('common', 'savedPatientForm'));
                    $this->redirect(array('answer/affichepatient'));
                } else {
                    Yii::app()->user->setFlash('erreur', Yii::t('common', 'notSavedPatientForm'));
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
            $model->last_updated = DateTime::createFromFormat(CommonTools::FRENCH_SHORT_DATE_FORMAT, date(CommonTools::FRENCH_SHORT_DATE_FORMAT));
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
            $gene = $model->addGene($nbMax, $gene);

            $analyse = new AnswerQuestion;
            $model->addAnalyse($nbMax, $analyse);

            $mutation = new AnswerQuestion;
            $model->addMutation($nbMax, $mutation);

            $comment = new AnswerQuestion;
            $model->addComment($nbMax, $comment);

            $model->addGeneToAnswers($model->answers_group, $gene, $analyse, $mutation, $comment);

            if ($model->save()) {
                Yii::app()->user->setFlash('succès', Yii::t('common', 'addGeneSuccess'));
            } else {
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'patientFormNotSaved'));
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
                Yii::app()->user->setFlash('succès', Yii::t('common', 'patientFormDeleted'));
            } else {
                echo "<div class='flash-success'>" . Yii::t('common', 'patientFormDeleted') . "</div>"; //for ajax
            }
        } catch (CDbException $e) {
            if (!isset($_GET['ajax'])) {
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'patientFormNotDeleted'));
            } else {
                echo "<div class='flash-error'>" . Yii::t('common', 'patientFormNotDeleted') . "</div>";
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
 
    /**
     * Do a search a selected question.
     */
    public function actionAddSearchFilter() {
        if (isset($_POST['question']) && !empty($_POST['question'])) {
            $id = $_POST['question'];
            $question = Answer::model()->findAllDetailledQuestionById($id);
            if ($question == null) {
                Yii::app()->controller->refresh();
            }
            echo QuestionnaireHTMLRenderer::renderQuestionForSearchHTML($question, 'fr', false);
        }
    }
    
    public function actionAddSearchReplaceFilter() {
        if (isset($_POST['question']) && !empty($_POST['question'])) {
            $id = $_POST['question'];
            $question = Answer::model()->findAllDetailledQuestionById($id);
            if ($question == null) {
                Yii::app()->controller->refresh();
            }
            echo QuestionnaireHTMLRenderer::renderQuestionForSearchReplaceHTML($question, 'fr', false);
        }
    }
 
    /**
     * Write queries (search filter)
     */
    public function actionWriteQueries() {
        $operators = array("equals" => "=", "noteq" => "<>", "less" => "<", "greater" => ">", "lesseq" => "<=", "greatereq" => ">=", "between" => "comprise entre", '$and' => Yii::t('common', 'and'), '$or' => Yii::t('common', 'or'));
        $mainQuestions = array();
        $questions = array();
        $condition = array();
        $compare = array();
        $dynamics = array();
        $html = "";
        $htmlQueries = "";
        $ok = false;
        if (isset($_POST['Answer']) && !empty($_POST['Answer'])) {
            echo (count($_POST['Answer']) == 1 && $_POST['Answer']['last_updated'] == "") ? "<h4 align=\"center\">" . Yii::t('common', 'noFilterSelected') . "</h4>" : "<h4 align=\"center\">" . Yii::t('common', 'query') . CHtml::image('images/disk.png', 'Export la requête', array('onclick'=>'document.forms["exportForm"].submit();', 'style'=>'float:right;margin-right:10px;margin-top:5px;width:20px;height:auto;')) . "</h4>";
            foreach ($_POST['Answer'] as $label => $answer) {
                if ($label == "last_updated" && $answer == "") {                 
                } elseif ($label != "condition" && $label != "compare" && $label != "dynamics") {
                    if (!is_array($answer)) {
                        $mainQuestions[$label] = $answer;
                    } else {
                        $mainQuestions[$label] = implode(', ', $answer);
                    }
                } else {
                    foreach ($answer as $key => $value) {
                        if ($label == "condition") {
                            $condition[$key] = $value;
                        }
                        if ($label == "compare") {
                            $compare[$key] = $value;
                        }
                        if ($label == "dynamics") {
                            $dynamics[$key] = $value;
                        }
                        if (!isset($compare[$key])) {
                            $compare[$key] = "";
                        }
                    }
                    foreach ($dynamics as $k => $v) {
                        if (gettype($v) == "array") {
                            $dynamics[$k] = implode(', ', $v);
                        } elseif (CommonTools::isDate($v)) {
                            $dynamics[$k] = str_replace('-', strtolower(Yii::t('common', 'and')), $dynamics[$k]);
                        }
                    }
                }
            }
            $questions = array_merge_recursive($condition, $compare, $dynamics);
            $html .= "<ul>";
            foreach ($mainQuestions as $label => $answer) {
                $html .= "<li>" . Answer::model()->attributeLabels()[$label] . " = " . $answer . "</li>";
                if ($answer != end($mainQuestions)) {
                    $html .= Yii::t('common', 'and');
                }
            }
            foreach ($questions as $key => $value) {
                if ($ok) {
                    $html .= $operators[$questions[$key][0]];
                } else {
                    $ok = true;
                }
                $html .= "<li>" . Answer::model()->getLabelQuestionById($key);
                foreach ($value as $label => $answer) {
                    if ($label != 0) {
                        if ($label == 1) {
                            $html .= ($answer != "") ? " " . $operators[$answer] : " = ";
                        }
                        if ($label == 2) {
                            $html .= " " . $answer . "</li>";
                        }
                    }
                }
            }
            $html .= "</ul>";
            $htmlQueries .= $html;
            $htmlQueries .= CHtml::form(Yii::app()->createUrl("answer/exportQueries"), "POST", array('id'=>'exportForm'));
            $htmlQueries .= CHtml::hiddenField('exportQueries', $html);
            $htmlQueries.= CHtml::endForm();
            echo $htmlQueries;
          
        }
    }

    public function actionExportQueries() {
        if (isset($_POST['exportQueries'])) {
            $html = Yii::t('common', 'exportQueriesDate');
            $word = explode(' ', $_POST['exportQueries'], 2);
            if ($word[0] == Yii::t('common', 'and') || $word[0] == Yii::t('common', 'or')) {
                $html .= $word[1];
            } else {
                $html .= $_POST['exportQueries'];
            }
            $html = str_replace("<ul><li>", '- ', $html);
            $html = str_replace("</li></ul>", '', $html);
            $html = str_replace("</li><li>", "\n- ", $html);
            $html = str_replace("</li>" . Yii::t('common', 'and') . "<li>", "\n" . Yii::t('common', 'and') . "\n- ", $html);
            $html = str_replace("</li>" . Yii::t('common', 'or') . "<li>", "\n" . Yii::t('common', 'and') . "\n- ", $html);
            $html = str_replace("<ul>" . Yii::t('common', 'and') . "<li>", "- ", $html);
            $html = str_replace("<ul>" . Yii::t('common', 'or') . "<li>", "- ", $html);
            Yii::app()->getRequest()->sendFile(date('Ymd_H') . 'h' . date('i') . "_queries_CBSD_Platform.txt", $html, "text/html; charset=UTF-8");
        }
    }
}