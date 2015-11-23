<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column1';

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    function init() {
        parent::init();
        $app = Yii::app();
        if (isset($_GET['lang'])) {
            $app->language = $_GET['lang'];
            $app->session['_lang'] = $app->language;
        } else
        if (isset($app->session['_lang'])) {
            $app->language = $app->session['_lang'];
        }
        if (isset($_GET['id']) && Yii::app()->controller->id == "questionnaire") {
            $criteria = new EMongoCriteria;
            $ficheQuestion = Questionnaire::model()->findByPk(New MongoId($_GET['id']));
            $_SESSION['idQuestion'] = $ficheQuestion;
        }
        if (isset($_GET['id']) && Yii::app()->controller->id == "answer") {
            $criteria = new EMongoCriteria;
            $fiche = Answer::model()->findByPk(New MongoId($_GET['id']));
            $_SESSION['id'] = $fiche;
        }
        if (isset($_POST['activeProfil'])) {
            if ($_POST['activeProfil'] === "newProfil") {
                $this->redirect('index.php?r=site/updatesubscribe');
            } else {
                $app->user->setState('activeProfil', $_POST['activeProfil']);
                if (Yii::app()->controller->id == "user" || Yii::app()->controller->id == "formulaire" || Yii::app()->controller->id == "fiche" || Yii::app()->controller->id == "questionBloc" || Yii::app()->controller->id == "administration" || Yii::app()->controller->id == "auditTrail" || Yii::app()->urlManager->parseUrl(Yii::app()->request) == "admin/admin") {
                    if (Yii::app()->user->getActiveProfil() != "administrateur") {
                        Yii::app()->user->setFlash('error', 'Vous n\'êtes pas autorisé à accéder à la page d\'administration');
                        $this->redirect('index.php?r=site/index');
                    }
                }

                if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "answer/view" || Yii::app()->urlManager->parseUrl(Yii::app()->request) == "answer/update") {
                    if ($_SESSION['id']->type == "clinique") {
                        if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "answer/view") {
                            if (!Yii::app()->user->isAuthorizedView($_POST['activeProfil'], "clinique")) {
                                Yii::app()->user->setFlash('error', 'Vous n\'êtes pas autorisé à consulter une fiche clinique');
                                $this->redirect('index.php?r=answer/affichepatient');
                            } else if (Yii::app()->user->id != $_SESSION['AnswerLogin']) {
                                Yii::app()->user->setFlash('error', 'Vous n\'êtes pas autorisé à consulter une fiche clinique qui ne vous appartient pas');
                                $this->redirect('index.php?r=answer/affichepatient');
                            } else
                                $this->redirect('index.php?r=answer/view&id=' . $_SESSION['id']->_id);
                        }
                        if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "answer/update") {
                            if (!Yii::app()->user->isAuthorizedUpdate($_POST['activeProfil'], "clinique")) {
                                Yii::app()->user->setFlash('error', 'Vous n\'êtes pas autorisé à modifier une fiche clinique');
                                $this->redirect('index.php?r=answer/affichepatient');
                            } else
                                $this->redirect('index.php?r=answer/update&id=' . $_SESSION['id']->_id);
                        }
                    }

                    if ($_SESSION['id']->type == "neuropathologique") {
                        if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "answer/view") {
                            if (!Yii::app()->user->isAuthorizedView($_POST['activeProfil'], "neuropathologique")) {
                                Yii::app()->user->setFlash('error', 'Vous n\'êtes pas autorisé à consulter une fiche neuropathologique');
                                $this->redirect('index.php?r=answer/affichepatient');
                            } else
                                $this->redirect('index.php?r=answer/view&id=' . $_SESSION['id']->_id);
                        }
                        if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "answer/update") {
                            if (!Yii::app()->user->isAuthorizedUpdate($_POST['activeProfil'], "neuropathologique")) {
                                Yii::app()->user->setFlash('error', 'Vous n\'êtes pas autorisé à modifier une fiche neuropathologique');
                                $this->redirect('index.php?r=answer/affichepatient');
                            } else
                                $this->redirect('index.php?r=answer/update&id=' . $_SESSION['id']->_id);
                        }
                    }

                    if ($_SESSION['id']->type == "genetique") {
                        if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "answer/view") {
                            if (!Yii::app()->user->isAuthorizedView($_POST['activeProfil'], "genetique")) {
                                Yii::app()->user->setFlash('error', 'Vous n\'êtes pas autorisé à consulter une fiche génétique');
                                $this->redirect('index.php?r=answer/affichepatient');
                            } else
                                $this->redirect('index.php?r=answer/view&id=' . $_SESSION['id']->_id);
                        }
                        if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "answer/update") {
                            if (!Yii::app()->user->isAuthorizedUpdate($_POST['activeProfil'], "genetique")) {
                                Yii::app()->user->setFlash('error', 'Vous n\'êtes pas autorisé à modifier une fiche génétique');
                                $this->redirect('index.php?r=answer/affichepatient');
                            } else
                                $this->redirect('index.php?r=answer/update&id=' . $_SESSION['id']->_id);
                        }
                    }
                }
                if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "questionnaire/update") {
                    if ($_SESSION['idQuestion']->type == "clinique") {
                        if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "questionnaire/update") {
                            if (!Yii::app()->user->isAuthorizedCreate($_POST['activeProfil'], "clinique")) {
                                Yii::app()->user->setFlash('error', 'Vous n\'êtes pas autorisé à créer une fiche clinique');
                                $this->redirect('index.php?r=answer/affichepatient');
                            } else
                                $this->redirect('index.php?r=questionnaire/update&id=' . $_SESSION['idQuestion']->_id);
                        }
                    }

                    if ($_SESSION['idQuestion']->type == "neuropathologique") {
                        if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "questionnaire/update") {
                            if (!Yii::app()->user->isAuthorizedCreate($_POST['activeProfil'], "neuropathologique")) {
                                Yii::app()->user->setFlash('error', 'Vous n\'êtes pas autorisé à créer une fiche neuropathologique');
                                $this->redirect('index.php?r=answer/affichepatient');
                            } else
                                $this->redirect('index.php?r=questionnaire/update&id=' . $_SESSION['idQuestion']->_id);
                        }
                    }

                    if ($_SESSION['idQuestion']->type == "genetique") {
                        if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "questionnaire/update") {
                            if (!Yii::app()->user->isAuthorizedCreate($_POST['activeProfil'], "genetique")) {
                                Yii::app()->user->setFlash('error', 'Vous n\'êtes pas autorisé à créer une fiche génétique');
                                $this->redirect('index.php?r=answer/affichepatient');
                            } else
                                $this->redirect('index.php?r=questionnaire/update&id=' . $_SESSION['idQuestion']->_id);
                        }
                    }
                }
            }
        }
    }

}