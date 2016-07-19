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

    public function init() {
        parent::init();
        $app = Yii::app();
        if (isset($_GET['lang'])) {
            $app->language = $_GET['lang'];
            $app->session['_lang'] = $app->language;
        } elseif (isset($app->session['_lang'])) {
            $app->language = $app->session['_lang'];
        }
        if (isset($_GET['id']) && Yii::app()->controller->id == "questionnaire") {
            $ficheQuestion = Questionnaire::model()->findByPk(new MongoId($_GET['id']));
            $_SESSION['idQuestion'] = $ficheQuestion;
        }
        if (isset($_GET['id']) && Yii::app()->controller->id == "answer") {
            $fiche = Answer::model()->findByPk(new MongoId($_GET['id']));
            $_SESSION['id'] = $fiche;
        }
        if (isset($_POST['activeProfil'])) {
            if ($_POST['activeProfil'] === "newProfil") {
                $this->redirect(array('site/updatesubscribe'));
            } else {
                $app->user->setState('activeProfil', $_POST['activeProfil']);
                $_SESSION['activeProfil'] = $_POST['activeProfil'];
                if (Yii::app()->controller->id == "rechercheFiche" && Yii::app()->user->getActiveProfil() == "clinicien") {
                    Yii::app()->user->setFlash('error', 'Vous n\'êtes pas autorisé à accéder à la liste des fiches disponibles');
                    $this->redirect(array('site/index'));
                }
                if (Yii::app()->controller->id == "user" || Yii::app()->controller->id == "formulaire" || Yii::app()->controller->id == "fiche" || Yii::app()->controller->id == "questionBloc" || Yii::app()->controller->id == "administration" || Yii::app()->controller->id == "auditTrail" || Yii::app()->urlManager->parseUrl(Yii::app()->request) == "admin/admin") {
                    if (Yii::app()->user->getActiveProfil() != "administrateur") {
                        Yii::app()->user->setFlash('error', 'Vous n\'êtes pas autorisé à accéder à la page d\'administration');
                        $this->redirect(array('site/index'));
                    }
                }
                if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "questionnaire/update") {
                    if ($_SESSION['idQuestion']->type == "clinique") {
                        if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "questionnaire/update") {
                            if (!Yii::app()->user->isAuthorizedCreate($_SESSION['activeProfil'], "clinique")) {
                                Yii::app()->user->setFlash('error', 'Vous n\'êtes pas autorisé à créer une fiche clinique');
                                $this->redirect(array('answer/affichepatient'));
                            } else {
                                $this->redirect('index.php?r=questionnaire/update&id=' . $_SESSION['idQuestion']->_id);
                            }
                        }
                    }
                    if ($_SESSION['idQuestion']->type == "neuropathologique") {
                        if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "questionnaire/update") {
                            if (!Yii::app()->user->isAuthorizedCreate($_SESSION['activeProfil'], "neuropathologique")) {
                                Yii::app()->user->setFlash('error', 'Vous n\'êtes pas autorisé à créer une fiche neuropathologique');
                                $this->redirect(array('answer/affichepatient'));
                            } else {
                                $this->redirect('index.php?r=questionnaire/update&id=' . $_SESSION['idQuestion']->_id);
                            }
                        }
                    }
                    if ($_SESSION['idQuestion']->type == "genetique") {
                        if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "questionnaire/update") {
                            if (!Yii::app()->user->isAuthorizedCreate($_SESSION['activeProfil'], "genetique")) {
                                Yii::app()->user->setFlash('error', 'Vous n\'êtes pas autorisé à créer une fiche génétique');
                                $this->redirect(array('answer/affichepatient'));
                            } else {
                                $this->redirect('index.php?r=questionnaire/update&id=' . $_SESSION['idQuestion']->_id);
                            }
                        }
                    }
                }
                if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "answer/view") {
                    if (!Yii::app()->user->isAuthorizedView($_SESSION['activeProfil'], $_SESSION['id']->type)) {
                        Yii::app()->user->setFlash('error', 'Vous n\'êtes pas autorisé à créer une fiche ' . $_SESSION['id']->type);
                        $this->redirect(array('answer/affichepatient'));
                    } else {
                        $this->redirect('index.php?r=answer/view&id=' . $_SESSION['id']->_id);
                    }
                }
                if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "answer/update") {
                    if (!Yii::app()->user->isAuthorizedUpdate($_SESSION['activeProfil'], $_SESSION['id']->type)) {
                        Yii::app()->user->setFlash('error', 'Vous n\'êtes pas autorisé à modifier une fiche ' . $_SESSION['id']->type);
                        $this->redirect(array('answer/affichepatient'));
                    } else {
                        $this->redirect('index.php?r=answer/update&id=' . $_SESSION['id']->_id);
                    }
                }
            }
        }
    }

}
