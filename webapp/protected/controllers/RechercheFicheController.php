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
        $model = new Answer;
        if (isset($_POST['Answer'])) {
            if (isset($_POST['Answer']['id_patient'])) {
                $_SESSION['idPatient'] = $_POST['Answer']['id_patient'];
            }
            if (isset($_POST['Answer']['type'])) {
                $_SESSION['typeForm'] = $_POST['Answer']['type'];
            }
            if (isset($_POST['Answer']['last_updated'])) {
                $_SESSION['Period'] = $_POST['Answer']['last_updated'];
            }
            $this->redirect(array('rechercheFiche/admin2'));
        }
        $this->render('admin', array(
            'model' => $model
        ));
    }

    public function actionAdmin2() {
        $criteria = new EMongoCriteria;
        if (isset($_POST['Answer'])) {
            $index = 0;
            $nbCriteria = array();
            foreach ($_POST['Answer']['dynamics'] as $questionId => $answerValue) {
                if ($answerValue != null && !empty($answerValue)) {
                    if ($index != 0) {
                        $nbCriteria = '$criteria' . $index;
                        $nbCriteria = new EMongoCriteria;
                    }
                    if (isset($_POST['Answer']['compare'][$questionId])) {
                        if ($index == 0) {
                            if ($_POST['Answer']['compare'][$questionId] == "between") {
                                $answerDate = CommonTools::formatDatePicker($answerValue);
                                $criteria->addCond('answers_group.answers', 'elemmatch', array('id' => $questionId, 'answer.date' => array('$gte' => $answerDate['date_from'] . " 00:00:00.000000", '$lte' => $answerDate['date_to'] . " 23:59:59.000000")));
                            } else {
                                $criteria->addCond('answers_group.answers', 'elemmatch', array('id' => $questionId, 'answer' => array(EMongoCriteria::$operators[$_POST['Answer']['compare'][$questionId]] => (int) $answerValue)));
                            }
                        } else {
                            if ($_POST['Answer']['compare'][$questionId] == "between") {
                                $answerDate = CommonTools::formatDatePicker($answerValue);
                                $nbCriteria->addCond('answers_group.answers', 'elemmatch', array('id' => $questionId, 'answer.date' => array('$gte' => $answerDate['date_from'] . " 00:00:00.000000", '$lte' => $answerDate['date_to'] . " 23:59:59.000000")));
                            } else {
                                $nbCriteria->addCond('answers_group.answers', 'elemmatch', array('id' => $questionId, 'answer' => array(EMongoCriteria::$operators[$_POST['Answer']['compare'][$questionId]] => (int) $answerValue)));
                            }
                        }
                    } else {
                        $values = (!is_array($answerValue)) ? split(',', $answerValue) : $answerValue;
                        if ($index == 0) {
                            $criteria->addCond('answers_group.answers', 'elemmatch', array('id' => $questionId, 'answer' => new MongoRegex(CommonTools::regexString($values))));
                        } else {
                            $nbCriteria->addCond('answers_group.answers', 'elemmatch', array('id' => $questionId, 'answer' => new MongoRegex(CommonTools::regexString($values))));
                        }
                    }
                }
                if ($index != 0) {
                    $criteria->mergeWith($nbCriteria, $_POST['Answer']['condition'][$questionId]);
                }
                $index++;
            }
            $criteria->sort('id_patient', EMongoCriteria::SORT_ASC);
            $criteria->sort('type', EMongoCriteria::SORT_ASC);
            $criteria->sort('last_updated', EMongoCriteria::SORT_DESC);
            Yii::app()->session['criteria'] = $criteria;
            $criteriaFiches = new EMongoCriteria($criteria);
            $dataProviderFiches = new EMongoDocumentDataProvider('Answer');
            $dataProviderFiches->setCriteria($criteriaFiches);
            $_SESSION['resultFiches'] = $dataProviderFiches;
            $this->redirect(array('rechercheFiche/admin3'));
        }
        $this->render('admin2');
    }

    public function actionAdmin3() {
        $model = new Answer;
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
        $this->render('admin3', array(
            'model' => $model,
            'dataProvider' => $_SESSION['resultFiches']
        ));
    }

    public function actionResultSearch() {
        $idPatient = array();
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
        $model = new Answer('search');
        $model->unsetAttributes();
        if (isset($_GET['Answer'])) {
            $model->attributes = $_GET['Answer'];
        }
        if (isset($_POST['rechercher'])) {
            if (isset($_POST['Answer_id_patient'])) {
                $criteria = new EMongoCriteria;
                $regex = '/^';
                foreach ($_POST['Answer_id_patient'] as $idPatient) {
                    $regex.= $idPatient . '$|^';
                }
                $regex .= '$/i';
                $criteria->addCond('id_patient', '==', new MongoRegex($regex));
                $_SESSION['id_patient'] = $regex;
            } else {
                $this->redirect(array('rechercheFiche/admin3'));
            }
        }
        $this->render('result_search', array(
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
        if (isset($_POST['exporter'])) {
            $filter = array();
            if (isset($_POST['filter'])) {
                $filter = $_POST['filter'];
            }
            $filename = date('Ymd_H') . 'h' . date('i') . '_liste_fiches_CBSD_Platform.csv';
            if (isset($_POST['project'])) {
                $project = new Project;
                $project->user = CommonTools::getUserLogin();
                $project->project_name = $_POST['project'];
                $project->file = $filename;
                $project->project_date = DateTime::createFromFormat(CommonTools::FRENCH_SHORT_DATE_FORMAT, date(CommonTools::FRENCH_SHORT_DATE_FORMAT));
                $project->save();
            }
            $arAnswers = Answer::model()->resultToArray($_SESSION['models'], $filter);
            $csv = new ECSVExport($arAnswers, true, false, null, null);
            $fh = fopen('protected/exported/' . $filename, 'a+');
            fwrite($fh, $csv->toCSV());
            fclose($fh);
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
        $model = new Answer;
        if (isset($_POST['result'])) {
            $old = $_POST['test'];
            $new = $_POST['result'];
            $test = $_POST['hidden_id'];
            $criteria = new EMongoCriteria;
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
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}
