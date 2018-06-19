<?php

Yii::import('ext.ECSVExport');

class RechercheFicheController extends Controller {

    /**
     * NB : boostrap theme need this column2 layout
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
     *
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('individualCases', 'admin', 'admin2', 'admin3', 'searchTranche', 'view', 'update', 'exportCsv', 'searchReplace', 'resultSearch', 'viewOnePage'),
                'expression' => '!Yii::app()->user->isGuest && $user->getActiveProfil() != "Clinicien"'
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndividualCases() {
        $query = Query::model()->find();
        if (!isset($_POST['searchAll']) && !isset($_POST['rechercher'])) {
            $_SESSION['id_patient'] = null;
            $_SESSION['patientAll'] = null;
            $_SESSION['id_patientAll'] = null;
            $_SESSION['criteria'] = null;
            if ($query != null) {
                $query->delete();
            }
        }
        $model = new Answer;
        $this->render('individualCases', array(
            'model' => $model
        ));
    }

    public function actionAdmin() {
        if (isset($_POST['searchTranche'])) {
            $this->redirect(array('rechercheFiche/searchTranche'));
        }
        $query = Query::model()->find();
        if (!isset($_POST['searchAll']) && !isset($_POST['rechercher'])) {
            $_SESSION['tranche'] = null;
            $_SESSION['id_patient'] = null;
            $_SESSION['patientAll'] = null;
            $_SESSION['id_patientAll'] = null;
            $_SESSION['criteria'] = null;
            if ($query != null) {
                $query->delete();
            }
        } elseif (isset($_POST['rechercher'])) {
            $htmlres = null;
            $htmlres .= "<li>" . Yii::t('common', 'anonymat') . " = ";
        } else {
            $query->html .= "<li>OU logique</li>";
            $ficheId = array();
            $query->id_patient = array();
            $ficheMongoId = array();
            $allFiches = Answer::model()->findAll(Yii::app()->session['criteria']);
            if ($allFiches != null) {
                foreach ($allFiches as $fiche) {
                    array_push($ficheId, $fiche->id_patient);
                    array_push($query->id_patient, $fiche->id_patient);
                    array_push($ficheMongoId, $fiche->questionnaireMongoId);
                    $fiche->save();
                    $query->save();
                }
                $regex = '/^';
                foreach ($ficheId as $idFiche) {
                    $regex .= $idFiche . '$|^';
                }
                $regex .= '$/i';
                $_SESSION['id_patientAll'] = $regex;
            }
        }
        $model = new Answer;

        $this->render('admin', array(
            'model' => $model,
            'query' => $query
        ));
    }

    public function actionAdmin2() {
        $_SESSION['test'] = null;
        $fiche = null;
        $htmlres = null;
        if (isset($_SESSION['checkedIds'])) {
            foreach ($_SESSION['checkedIds'] as $ar) {
                Yii::app()->user->setState($ar, 0);
            }
        }
        $query = Query::model()->find();
        if ($query == null) {
            $query = new Query;
        }

        // Restreindre la requête -> Formuler la requête
        if (isset($_POST['Answer'])) {
            $_SESSION['Answer'] = $_POST['Answer'];
            $criteria = new EMongoCriteria;
            if (isset($_POST['Answer']['type']) && $_POST['Answer']['type'] != null) {
                if ($query->type != null) {
                    array_push($query->type, $_POST['Answer']['type']);
                } else {
                    $forms = array();
                    array_push($forms, $_POST['Answer']['type']);
                    $query->type = $forms;
                }
                $htmlres .= "<li>" . Yii::t('common', 'formName') . " = " . $_POST['Answer']['type'] . "</li>";
            }
            if (isset($_POST['Answer']['last_updated']) && $_POST['Answer']['last_updated'] != null && isset($_POST['Answer']['last_updated_to']) && $_POST['Answer']['last_updated_to'] != null) {
                $query->last_updated = $_POST['Answer']['last_updated'] . " - " . $_POST['Answer']['last_updated_to'];
                $answerFormat = CommonTools::formatDatePicker($query->last_updated);
                $date_from = str_replace('/', '-', $answerFormat['date_from']);
                $date_to = str_replace('/', '-', $answerFormat['date_to']);
                $criteria->last_updated->date = array('$gte' => date('Y-m-d', strtotime($date_from)) . " 00:00:00.000000", '$lte' => date('Y-m-d', strtotime($date_to)) . " 23:59:59.000000");
                $htmlres .= "<li>" . Yii::t('common', 'period') . " = " . $_POST['Answer']['last_updated'] . " - " . $_POST['Answer']['last_updated_to'] . "</li>";
            }
            $query->html .= $htmlres;
            $query->save();
        }

        $html = Query::model()->find();

        if (isset($_POST['runQuery'])) {
            $this->redirect(array('rechercheFiche/admin3'));
        }
        $this->render('admin2', array('fiche' => ($fiche != null) ? $fiche : null, 'html' => $html, 'type' => $_POST['Answer']['type']));
    }

    public function actionAdmin3() {
        $html = Query::model()->find();
        $query = new Query;
        $model = new Answer('search');
        $model->unsetAttributes();
        if (isset($_POST['Answer']['id_patient']) && $_POST['Answer']['id_patient'] != null) {
            $htmlres = null;
            $query->id_patient = $_POST['Answer']['id_patient'];
            $query->save();
            $htmlres .= "<li>" . Yii::t('common', 'anonymat') . " = ";
            foreach ($_POST['Answer']['id_patient'] as $idPatient) {
                $htmlres .= $idPatient;
                if ($idPatient != end($_POST['Answer']['id_patient'])) {
                    $htmlres .= ", ";
                }
            }
            $htmlres .= "</li>";
        }
        if (!isset($_GET['ajax'])) {
            if (isset($_SESSION['checkedIds'])) {
                foreach ($_SESSION['checkedIds'] as $ar) {
                    Yii::app()->user->setState($ar, 0);
                }
            }
        }
        if (isset($_GET['checkedIds']) && !empty($_GET['checkedIds'])) {
            CommonTools::chkIds($_GET['checkedIds']);
        }
        if (isset($_GET['uncheckedIds']) && !empty($_GET['uncheckedIds'])) {
            CommonTools::unckIds($_GET['uncheckedIds']);
        }
        if (isset($_SESSION['Answer'])) {
            $model->setAttributes($_SESSION['Answer']);
        }
        $this->render('admin3', array(
            'model' => $model,
            'html' => $html
        ));
    }

    public function actionSearchTranche() {
        $_SESSION['tranche'] = null;
        $model = new Tranche;
        $query = Query::model()->find();
        $query->id_patient = array();
        $idDonor = array();
        $allFiches = Answer::model()->findAll(Yii::app()->session['criteria']);
        if ($allFiches != null) {
            foreach ($allFiches as $fiche) {
                array_push($query->id_patient, $fiche->id_patient);
                $neuropath = Neuropath::model()->findByAttributes(array('id_cbsd' => (int) $fiche->id_patient));
                if ($neuropath != null) {
                    array_push($idDonor, $neuropath->id_donor);
                }
                $query->save();
            }
            $regex = '/^';
            foreach ($idDonor as $value) {
                $regex .= $value . '$';
                if ($value !== end($idDonor)) {
                    $regex .= '|^';
                }
            }
            $regex .= '/i';
        }
        $criteria = new EMongoCriteria;
        if ($idDonor != null) {
            $criteria->id_donor = new MongoRegex($regex);
        }
        if (isset($_POST['Tranche']['originSamplesTissue']) && $_POST['Tranche']['originSamplesTissue'] != null) {
            $criteria->originSamplesTissue = $_POST['Tranche']['originSamplesTissue'];
        }
        if (isset($_POST['Tranche']['quantityAvailable']) && $_POST['Tranche']['quantityAvailable'] != null) {
            $criteria->quantityAvailable = $_POST['Tranche']['quantityAvailable'];
        }
        if (isset($_POST['Tranche']['storageConditions']) && $_POST['Tranche']['storageConditions'] != null) {
            $criteria->storageConditions = $_POST['Tranche']['storageConditions'];
        }
        $tranche = Tranche::model()->findAll($criteria);
        $_SESSION['tranche'] = $tranche;
        if (isset($_POST['Tranche'])) {
            $this->redirect(array("rechercheFiche/admin3"));
        }
        $this->render('searchTranche', array(
            'model' => $model
        ));
    }

    /**
     * Affiche une fiche ,en  lecture uniquement
     * @param $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $model = Answer::model()->findByPk(new MongoID($id));
        $this->render('view', array(
            'model' => $model,
        ));
    }

    public function actionUpdate($id) {
        $model = Answer::model()->findByPk(new MongoID($id));
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
                    $this->redirect(array('rechercheFiche/admin'));
                } else {
                    Yii::app()->user->setFlash('erreur', Yii::t('common', 'notSavedPatientForm'));
                }
            }
        }
        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionViewOnePage($id) {
        $model = Answer::model()->findByPk(new MongoID($id));
        $this->render('view_onepage', array(
            'model' => $model,
        ));
    }

    /**
     * export csv liste des fiches disponibles
     */
    public function actionExportCsv() {
        /*if (isset($_POST['hiddenFields'])) {
            $test = array();
            $test = explode('&&', $_POST['hiddenFields']);
        }*/
        if (isset($_POST['exporter'])) {
            $filter = array();
            //if (isset($_POST['filter'])) {
                if (isset($_POST['hiddenFields'])) {
            $filter = explode('&&', $_POST['hiddenFields']);
        }
            //}
            $filename = date('Ymd_H') . 'h' . date('i') . '_liste_fiches_CBSD_Platform.csv';
            $arAnswers = Answer::model()->resultToArray($_SESSION['models'], $filter);
            $csv = new ECSVExport($arAnswers, true, false, null, null);
            if (isset($_POST['project'])) {
                $project = new Project;
                $project->user = CommonTools::getUserLogin();
                $project->project_name = $_POST['project'];
                $project->file = $filename;
                $project->project_date = DateTime::createFromFormat(CommonTools::FRENCH_SHORT_DATE_FORMAT, date(CommonTools::FRENCH_SHORT_DATE_FORMAT));
                $project->save();
                $fh = fopen('protected/exported/' . $filename, 'a+');
                fwrite($fh, $csv->toCSV());
                fclose($fh);
            }
            Yii::app()->getRequest()->sendFile($filename, "\xEF\xBB\xBF" . $csv->toCSV(), "text/csv; charset=UTF-8", false);
        } elseif (isset($_POST['exportTranche'])) {
            $filename = date('Ymd_H') . 'h' . date('i') . '_liste_fiches_CBSD_Platform_TRANCHE.csv';
            $arAnswers = Answer::model()->resultExportTranche($_SESSION['models']);
        }
        $model = new Answer('search');
        $model->unsetAttributes();
        if (isset($_GET['Answer'])) {
            $model->attributes = $_GET['Answer'];
        }
        if (isset($_SESSION['criteria']) && $_SESSION['criteria'] != null && $_SESSION['criteria'] instanceof EMongoCriteria) {
            $criteria = $_SESSION['criteria'];
        } else {
            $criteria = new EMongoCriteria;
        }
        // trier par id_patient et type de fiche dans l'ordre croissant
        $criteria->sort('id_patient', EMongoCriteria::SORT_ASC);
        $criteria->sort('type', EMongoCriteria::SORT_ASC);
        $models = Answer::model()->findAll($criteria);
        $_SESSION['models'] = $models;
        if (count($models) < 1) {
            Yii::app()->user->setFlash("erreur", Yii::t('common', 'emptyPatientFormExport'));
            $this->redirect(array("rechercheFiche/admin"));
        }
        $this->render('exportFilter', array(
            'models' => $models,
        ));
    }

    public function actionSearchReplace() {
        if (isset($_POST['result'])) {
            $old = $_POST['test'];
            $new = $_POST['result'];
            $test = $_POST['hidden_id'];
            $criteria = $_SESSION['criteria'];
            $criteria1 = new EMongoCriteria;
            $criteria1->addCond('answers_group.answers.id', '==', $test);
            $criteria->mergeWith($criteria1);
            $model = Answer::model()->findAll($criteria);
            foreach ($model as $k) {
                foreach ($k->answers_group as $answers) {
                    foreach ($answers->answers as $a) {
                        if (!is_array($a->answer)) {
                            if (($test == $a->id) && ($old == $a->answer)) {
                                $a->answer = $new;
                                if ($k->save()) {
                                    Yii::app()->user->setFlash("succès", 'La valeur de la variable a bien été mise à jour.');
                                } else {
                                    Yii::app()->user->setFlash("erreur", 'Une erreur s\'est produite.');
                                }
                            }
                        } elseif ($a->type == 'date') {
                            if (($test == $a->id) && ($old == $a->answer['date'])) {
                                $a->answer['date'] = $new;
                                if ($k->save()) {
                                    Yii::app()->user->setFlash("succès", 'La valeur de la variable a bien été mise à jour.');
                                } else {
                                    Yii::app()->user->setFlash("erreur", 'Une erreur s\'est produite.');
                                }
                            }
                        } elseif ($a->type == 'checkbox') {
                            $temp = array();
                            if (($test == $a->id) && (in_array($old, $a->answer))) {
                                foreach ($a->answer as $v) {
                                    if ($old != $v) {
                                        array_push($temp, $v);
                                    }
                                }
                                if (!in_array($new, $temp)) {
                                    array_push($temp, $new);
                                }
                                $a->answer = $temp;
                                if ($k->save()) {
                                    Yii::app()->user->setFlash("succès", 'La valeur de la variable a bien été mise à jour.');
                                } else {
                                    Yii::app()->user->setFlash("erreur", 'Une erreur s\'est produite.');
                                }
                            }
                        }
                    }
                }
            }
        }
        if (isset($_SESSION['criteria']) && $_SESSION['criteria'] != null && $_SESSION['criteria'] instanceof EMongoCriteria) {
            $criteria = $_SESSION['criteria'];
        } else {
            $criteria = new EMongoCriteria;
        }
        $model = Answer::model()->findAll($criteria);
        if ($model != null) {
            $_SESSION['model'] = $model;
        }
        $this->render('searchReplace', array(
            'model' => $model,
        ));
    }

    public function loadModel($id) {
        $model = Answer::model()->findByPk(new MongoID($id));
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

}