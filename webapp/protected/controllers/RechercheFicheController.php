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
                'actions' => array('admin', 'admin2', 'admin3', 'view', 'update', 'exportCsv', 'searchReplace', 'resultSearch', 'viewOnePage'),
                'expression' => '!Yii::app()->user->isGuest && $user->getActiveProfil() != "Clinicien"'
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionAdmin() {
        $_SESSION['id_patientBis'] = null;
        $_SESSION['id_patientAll'] = null;
        $_SESSION['id_patient'] = null;
        $_SESSION['typeForm'] = null;
        $_SESSION['last_updated'] = null;
        $_SESSION['html'] = null;
        $_SESSION['formulateQuery'] = null;
        $_SESSION['Available'] = null;
        $_SESSION['htmlAvailable'] = null;
        $model = new Answer;
        $this->render('admin', array(
            'model' => $model
        ));
    }

    public function actionAdmin2() {
        $fiche = null;
        $html = "<ul>";
        $htmlPrvmt = "";
        if (isset($_POST['Answer'])) {
            $_SESSION['Answer'] = $_POST['Answer'];
            $criteria = new EMongoCriteria;
            if (isset($_POST['Answer']['id_patient']) && $_POST['Answer']['id_patient'] != null) {
                $_SESSION['id_patient'] = $_POST['Answer']['id_patient'];
                $criteria->addCond('id_patient', '==', new MongoRegex(CommonTools::regexString($_POST['Answer']['id_patient'])));
                $html .= "<li>" . Yii::t('common', 'anonymat') . " = ";
                foreach ($_POST['Answer']['id_patient'] as $idPatient) {
                    $html .= $idPatient;
                    if ($idPatient != end($_POST['Answer']['id_patient'])) {
                        $html .= ", ";
                    }
                }
                $html .= "</li>";
            }
            if (isset($_POST['Answer']['type']) && $_POST['Answer']['type'] != null) {
                $_SESSION['typeForm'] = $_POST['Answer']['type'];
                $criteria->addCond('type', '==', new MongoRegex(CommonTools::regexString($_POST['Answer']['type'])));
                $html .= "<li>" . Yii::t('common', 'formType') . " = ";
                foreach ($_POST['Answer']['type'] as $typeForm) {
                    $html .= $typeForm;
                    if ($typeForm != end($_POST['Answer']['type'])) {
                        $html .= ", ";
                    }
                }
                $html .= "</li>";
            }
            if (isset($_POST['Answer']['last_updated']) && $_POST['Answer']['last_updated'] != null) {
                $_SESSION['last_updated'] = $_POST['Answer']['last_updated'];
                $answerFormat = CommonTools::formatDatePicker($_POST['Answer']['last_updated']);
                $date_from = str_replace('/', '-', $answerFormat['date_from']);
                $date_to = str_replace('/', '-', $answerFormat['date_to']);
                $criteria->last_updated->date = array('$gte' => date('Y-m-d', strtotime($date_from)) . " 00:00:00.000000", '$lte' => date('Y-m-d', strtotime($date_to)) . " 23:59:59.000000");
                $html .= "<li>" . Yii::t('common', 'period') . " = " . $_POST['Answer']['last_updated'] . "</li>";
            }
            $html .= "</ul>";
            $criteria->sort('id_patient', EMongoCriteria::SORT_ASC);
            $criteria->sort('type', EMongoCriteria::SORT_ASC);
            $criteria->sort('last_updated', EMongoCriteria::SORT_DESC);
        }
        if ($html == "<ul></ul>") {
            $html .= "Pas de restriction.";
        }
        if (isset($_SESSION['html'])) {
            $html = $_SESSION['html'];
        } else {
            $_SESSION['html'] = $html;
        }

        if (isset($_POST['rechercher'])) {
            $patientId = array();
            if (isset($_POST['Answer_id_patient'])) {
                foreach ($_POST['Answer_id_patient'] as $patient_id) {
                    $id = Answer::model()->findByPk(new MongoID($patient_id));
                    if ($id != null) {
                        array_push($patientId, $id->id_patient);
                    }
                }
                if (isset($_SESSION['checkedIds'])) {
                    foreach ($_SESSION['checkedIds'] as $patient_idBis) {
                        $idBis = Answer::model()->findByPk(new MongoID($patient_idBis));
                        if ($idBis != null) {
                            array_push($patientId, $idBis->id_patient);
                        }
                    }
                }
                $regex = '/^';
                foreach ($patientId as $idPatient) {
                    $regex .= $idPatient . '$|^';
                }
                $regex .= '$/i';
                $_SESSION['id_patientBis'] = $regex;
            } else {
                $this->redirect(array('rechercheFiche/admin3'));
            }
        }
        $htmlPrvmt .= "<ul>";
        if (isset($_POST['Available'])) {
            $_SESSION['Available'] = $_POST['Available'];
            foreach ($_SESSION['Available'] as $kAvailable => $vAvailable) {
                $htmlPrvmt .= "<li>" . $vAvailable . " = Available</li>";
            }
        }
        $htmlPrvmt .= "</ul>";
        $_SESSION['htmlAvailable'] = $htmlPrvmt;
        
        if (isset($_POST['searchAll']) && isset(Yii::app()->session['criteria'])) {
            $ficheId = array();
            $allFiches = Answer::model()->findAll(Yii::app()->session['criteria']);
            if ($allFiches != null) {
                foreach ($allFiches as $fiche) {
                    array_push($ficheId, $fiche->id_patient);
                }
                $regex = '/^';
                foreach ($ficheId as $idFiche) {
                    $regex .= $idFiche . '$|^';
                }
                $regex .= '$/i';
                $_SESSION['id_patientAll'] = $regex;
            }
        }
        if (isset($_POST['question']) && $_POST['question'] != null || isset($_POST['Available']) && $_POST['Available'] != null || isset($_POST['NotAvailable']) && $_POST['NotAvailable'] != null) {
            $this->redirect(array('rechercheFiche/admin3'));
        }
        $this->render('admin2', array('fiche' => ($fiche != null) ? $fiche : null, 'html' => $html));
    }

    public function actionAdmin3() {
        $model = new Answer('search');
        $model->unsetAttributes();
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
        if (isset($_POST['exporter'])) {
            $filter = array();
            if (isset($_POST['filter'])) {
                $filter = $_POST['filter'];
            }
            $filename = date('Ymd_H') . 'h' . date('i') . '_liste_fiches_CBSD_Platform.csv';
            $arAnswers = Answer::model()->resultToArray($_SESSION['models'], $filter);
            $csv = new ECSVExport($arAnswers, true, false, null, null);
            Yii::app()->getRequest()->sendFile($filename, "\xEF\xBB\xBF" . $csv->toCSV(), "text/csv; charset=UTF-8", false);
        }
        if (isset($_SESSION['Answer'])) {
            $model->setAttributes($_SESSION['Answer']);
        }
        $this->render('admin3', array(
            'model' => $model
        ));
    }

    public function actionResultSearch() {
        if (isset($_POST['rechercher'])) {
            $patientId = array();
            if (isset($_POST['Answer_id_patient'])) {
                foreach ($_POST['Answer_id_patient'] as $patient_id) {
                    $id = Answer::model()->findByPk(new MongoID($patient_id));
                    if ($id != null) {
                        array_push($patientId, $id->id_patient);
                    }
                }
                if (isset($_SESSION['checkedIds'])) {
                    foreach ($_SESSION['checkedIds'] as $patient_idBis) {
                        $idBis = Answer::model()->findByPk(new MongoID($patient_idBis));
                        if ($idBis != null) {
                            array_push($patientId, $idBis->id_patient);
                        }
                    }
                }
                $regex = '/^';
                foreach ($patientId as $idPatient) {
                    $regex .= $idPatient . '$|^';
                }
                $regex .= '$/i';
                $_SESSION['id_patientBis'] = $regex;
            } else {
                $this->redirect(array('rechercheFiche/admin3'));
            }
        }
        if (isset($_POST['question']) && $_POST['question'] == null) {
            $this->redirect(array('rechercheFiche/admin3'));
        }
        if (isset($_POST['Answer'])) {
            $_SESSION['Answer'] = $_POST['Answer'];
            $this->redirect(array('rechercheFiche/admin3'));
        }
        $this->render('_search_filter');
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
        if (isset($_POST['exporter'])) {
            $filter = array();
            if (isset($_POST['filter'])) {
                $filter = $_POST['filter'];
            }
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
