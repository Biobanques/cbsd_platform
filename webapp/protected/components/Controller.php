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

        $controller = Yii::app()->getController()->getId();
        
        if (isset($_GET['id'])) {
            $action = explode('/', Yii::app()->urlManager->parseUrl(Yii::app()->request))[1];
            if ($controller == 'questionnaire') {
                $questionnaire = Yii::app()->user->getQuestionnaireById($_GET['id']);
                if ($action == 'update') {
                    switch ($questionnaire->type) {
                        case "clinique":
                            if (!Yii::app()->user->isAuthorizedCreate(Yii::app()->user->getState('activeProfil'), "clinique")) {
                                Yii::app()->user->setFlash("erreur", Yii::t('common', 'notAllowCreateClinicalPatientForm'));
                                $this->redirect(array('answer/affichepatient'));
                            }
                            break;
                        case "neuropathologique":
                            if (!Yii::app()->user->isAuthorizedCreate(Yii::app()->user->getState('activeProfil'), "neuropathologique")) {
                                Yii::app()->user->setFlash("erreur", Yii::t('common', 'notAllowCreateNeuropathologicalPatientForm'));
                                $this->redirect(array('answer/affichepatient'));
                            }
                            break;
                        case "genetique":
                            if (!Yii::app()->user->isAuthorizedCreate(Yii::app()->user->getState('activeProfil'), "genetique")) {
                                Yii::app()->user->setFlash("erreur", Yii::t('common', 'notAllowCreateGeneticPatientForm'));
                                $this->redirect(array('answer/affichepatient'));
                            }
                            break;
                    }
                }
            }

            if ($controller == 'answer') {
                $fiche = Yii::app()->user->getFicheById($_GET['id']);
                if ($action == 'view' || $action == 'update') {
                    switch ($fiche->type) {
                        case "clinique":
                            if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "answer/view") {
                                if (!Yii::app()->user->isAuthorizedView(Yii::app()->user->getState('activeProfil'), "clinique")) {
                                    Yii::app()->user->setFlash("erreur", Yii::t('common', 'notAllowViewClinicalPatientForm'));
                                    $this->redirect(array('answer/affichepatient'));
                                } elseif (Yii::app()->user->getState('activeProfil') == "Clinicien" && Yii::app()->user->id != $fiche->login) {
                                    Yii::app()->user->setFlash("erreur", Yii::t('common', 'notAllowViewSelfClinicalPatientForm'));
                                    $this->redirect(array('answer/affichepatient'));
                                }
                            }
                            if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "answer/update") {
                                if (!Yii::app()->user->isAuthorizedUpdate(Yii::app()->user->getState('activeProfil'), "clinique")) {
                                    Yii::app()->user->setFlash("erreur", Yii::t('common', 'notAllowUpdateClinicalPatientForm'));
                                    $this->redirect(array('answer/affichepatient'));
                                }
                            }
                            break;
                        case "neuropathologique":
                            if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "answer/view") {
                                if (!Yii::app()->user->isAuthorizedView(Yii::app()->user->getState('activeProfil'), "neuropathologique")) {
                                    Yii::app()->user->setFlash("erreur", Yii::t('common', 'notAllowViewNeuropathologicalPatientForm'));
                                    $this->redirect(array('answer/affichepatient'));
                                }
                            }
                            if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "answer/update") {
                                if (!Yii::app()->user->isAuthorizedUpdate(Yii::app()->user->getState('activeProfil'), "neuropathologique")) {
                                    Yii::app()->user->setFlash("erreur", Yii::t('common', 'notAllowUpdateNeuropathologicalPatientForm'));
                                    $this->redirect(array('answer/affichepatient'));
                                }
                            }
                            break;
                        case "genetique":
                            if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "answer/view") {
                                if (!Yii::app()->user->isAuthorizedView(Yii::app()->user->getState('activeProfil'), "genetique")) {
                                    Yii::app()->user->setFlash("erreur", Yii::t('common', 'notAllowViewGeneticPatientForm'));
                                    $this->redirect(array('answer/affichepatient'));
                                }
                            }
                            if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "answer/update") {
                                if (!Yii::app()->user->isAuthorizedUpdate(Yii::app()->user->getState('activeProfil'), "genetique")) {
                                    Yii::app()->user->setFlash("erreur", Yii::t('common', 'notAllowUpdateGeneticPatientForm'));
                                    $this->redirect(array('answer/affichepatient'));
                                }
                            }
                            break;
                    }
                }
            }
        }


        if (isset($_POST['activeProfil'])) {
            if ($_POST['activeProfil'] === "newProfil") {
                $this->redirect('index.php?r=site/updatesubscribe');
            } else {
                $app->user->setState('activeProfil', $_POST['activeProfil']);
                if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "answer/affichepatient") {
                    $this->redirect(array('answer/affichepatient'));
                } else {
                    if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "site/patient" || Yii::app()->urlManager->parseUrl(Yii::app()->request) == "answer/affichepatient" && !Yii::app()->user->isAuthorizedViewPatientNavbar()) {
                        Yii::app()->user->setFlash("erreur", Yii::t('common', 'notAllowAccessPage'));
                        $this->redirect(array('site/index'));
                    }
                    if (Yii::app()->controller->id == "user" || Yii::app()->controller->id == "formulaire" || Yii::app()->controller->id == "fiche" || Yii::app()->controller->id == "questionBloc" || Yii::app()->controller->id == "administration" || Yii::app()->controller->id == "auditTrail" || Yii::app()->urlManager->parseUrl(Yii::app()->request) == "admin/admin") {
                        if (!Yii::app()->user->isAdmin()) {
                            Yii::app()->user->setFlash("erreur", Yii::t('common', 'notAllowManagement'));
                            $this->redirect(array('site/index'));
                        }
                    }
                    if (Yii::app()->user->getActiveProfil() == "chercheur") {
                        if (Yii::app()->controller->id == "questionnaire" || Yii::app()->controller->id == "answer") {
                            Yii::app()->user->setFlash("erreur", Yii::t('common', 'notAllowAccessPage'));
                            $this->redirect(array('site/index'));
                        }
                    }
                    if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "answer/view" || Yii::app()->urlManager->parseUrl(Yii::app()->request) == "answer/update") {
                        if ($fiche->type == "clinique") {
                            if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "answer/view") {
                                if (!Yii::app()->user->isAuthorizedView($_POST['activeProfil'], "clinique")) {
                                    Yii::app()->user->setFlash("erreur", Yii::t('common', 'notAllowViewClinicalPatientForm'));
                                    $this->redirect(array('answer/affichepatient'));
                                } elseif ($_POST['activeProfil'] == "Clinicien" && Yii::app()->user->id != $fiche->login) {
                                    Yii::app()->user->setFlash("erreur", Yii::t('common', 'notAllowViewSelfClinicalPatientForm'));
                                    $this->redirect(array('answer/affichepatient'));
                                }
                                $this->redirect('index.php?r=answer/view&id=' . $fiche->_id);
                            }
                            if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "answer/update") {
                                if (!Yii::app()->user->isAuthorizedUpdate($_POST['activeProfil'], "clinique")) {
                                    Yii::app()->user->setFlash("erreur", Yii::t('common', 'notAllowUpdateClinicalPatientForm'));
                                    $this->redirect(array('answer/affichepatient'));
                                } else {
                                    $this->redirect('index.php?r=answer/update&id=' . $fiche->_id);
                                }
                            }
                        }

                        if ($fiche->type == "neuropathologique") {
                            if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "answer/view") {
                                if (!Yii::app()->user->isAuthorizedView($_POST['activeProfil'], "neuropathologique")) {
                                    Yii::app()->user->setFlash("erreur", Yii::t('common', 'notAllowViewNeuropathologicalPatientForm'));
                                    $this->redirect(array('answer/affichepatient'));
                                } else {
                                    $this->redirect('index.php?r=answer/view&id=' . $fiche->_id);
                                }
                            }
                            if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "answer/update") {
                                if (!Yii::app()->user->isAuthorizedUpdate($_POST['activeProfil'], "neuropathologique")) {
                                    Yii::app()->user->setFlash("erreur", Yii::t('common', 'notAllowUpdateNeuropathologicalPatientForm'));
                                    $this->redirect(array('answer/affichepatient'));
                                } else {
                                    $this->redirect('index.php?r=answer/update&id=' . $fiche->_id);
                                }
                            }
                        }

                        if ($fiche->type == "genetique") {
                            if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "answer/view") {
                                if (!Yii::app()->user->isAuthorizedView($_POST['activeProfil'], "genetique")) {
                                    Yii::app()->user->setFlash("erreur", Yii::t('common', 'notAllowViewGeneticPatientForm'));
                                    $this->redirect(array('answer/affichepatient'));
                                } else {
                                    $this->redirect('index.php?r=answer/view&id=' . $fiche->_id);
                                }
                            }
                            if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "answer/update") {
                                if (!Yii::app()->user->isAuthorizedUpdate($_POST['activeProfil'], "genetique")) {
                                    Yii::app()->user->setFlash("erreur", Yii::t('common', 'notAllowUpdateGeneticPatientForm'));
                                    $this->redirect(array('answer/affichepatient'));
                                } else {
                                    $this->redirect('index.php?r=answer/update&id=' . $fiche->_id);
                                }
                            }
                        }
                    }
                    if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "questionnaire/update") {
                        if ($_SESSION['idQuestion']->type == "clinique") {
                            if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "questionnaire/update") {
                                if (!Yii::app()->user->isAuthorizedCreate($_POST['activeProfil'], "clinique")) {
                                    Yii::app()->user->setFlash("erreur", Yii::t('common', 'notAllowCreateClinicalPatientForm'));
                                    $this->redirect(array('answer/affichepatient'));
                                } else {
                                    $this->redirect('index.php?r=questionnaire/update&id=' . $questionnaire->_id);
                                }
                            }
                        }

                        if ($_SESSION['idQuestion']->type == "neuropathologique") {
                            if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "questionnaire/update") {
                                if (!Yii::app()->user->isAuthorizedCreate($_POST['activeProfil'], "neuropathologique")) {
                                    Yii::app()->user->setFlash("erreur", Yii::t('common', 'notAllowCreateNeuropathologicalPatientForm'));
                                    $this->redirect(array('answer/affichepatient'));
                                } else {
                                    $this->redirect('index.php?r=questionnaire/update&id=' . $questionnaire->_id);
                                }
                            }
                        }

                        if ($_SESSION['idQuestion']->type == "genetique") {
                            if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "questionnaire/update") {
                                if (!Yii::app()->user->isAuthorizedCreate($_POST['activeProfil'], "genetique")) {
                                    Yii::app()->user->setFlash("erreur", Yii::t('common', 'notAllowCreateGeneticPatientForm'));
                                    $this->redirect(array('answer/affichepatient'));
                                } else {
                                    $this->redirect('index.php?r=questionnaire/update&id=' . $questionnaire->_id);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

}
