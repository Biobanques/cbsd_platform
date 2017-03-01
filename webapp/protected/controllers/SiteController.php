<?php

class SiteController extends Controller
{
    /**
     * NB : boostrap theme need this column2 layout
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
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
    public function accessRules()
    {
        return array(
            array(
                'allow',
                'actions' => array(
                    'index',
                    'login',
                    'logout',
                    'error',
                    'recoverPwd',
                    'subscribe',
                    'subscribeProfil',
                    'confirmUser',
                    'refuseUser'
                ),
                'users' => array(
                    '*'
                )
            ),
            array(
                'allow',
                'actions' => array(
                    'setActiveProfil',
                    'updateSubscribe'
                ),
                'users' => array(
                    '@'
                )
            ),
            array(
                'allow',
                'actions' => array(
                    'patient'
                ),
                'expression' => '$user->getActiveProfil() != "chercheur" && $user->getActiveProfil() != ""'
            ),
            array(
                'deny', // deny all users
                'users' => array(
                    '*'
                )
            )
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'

        $this->render('index');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error == Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the search patient page
     */
    public function actionPatient() {
        $model = new PatientForm;
        if (isset($_POST['PatientForm'])) {
            $model->attributes = $_POST['PatientForm'];
        }
        $this->render('patient', array('model' => $model, 'actionForm' => 'search'));
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        $model = new LoginForm;
        
        $userLog = new UserLog;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            $criteria = new EMongoCriteria;
            $criteria->login = $model->username;
            $criteria->password = $model->password;
            $user = User::model()->find($criteria);
            // validate user input and redirect to the previous page if valid
            if ($model->validate()) {
                if (count($user->profil) == 0) {
                    Yii::app()->user->setFlash('erreur', Yii::t('common', 'contactAdministrator'));
                } elseif ($model->login()) {
                    $userLog->user = Yii::app()->user->getNomPrenom();
                    $userLog->ipAddress = $_SERVER['REMOTE_ADDR'];
                    $userLog->profil = Yii::app()->user->getActiveProfil();
                    $userLog->connectionDate = DateTime::createFromFormat('d/m/Y', date('d/m/Y'));
                    $userLog->save();
                    $this->redirect(array('site/index'));
                }
            } else {
                    Yii::app()->user->setFlash('erreur', Yii::t('common', 'incorrectLoginPassword'));
            }
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

// username and password are required
    // rememberMe needs to be a boolean
    /**
     * display the recover password page
     */
    public function actionRecoverPwd()
    {
        $model = new RecoverPwdForm();
        $result = '';
        if (isset($_POST['RecoverPwdForm'])) {
            $model->attributes = $_POST['RecoverPwdForm'];
            if ($model->validate()) {
                $mixedResult = $model->validateFields();
                if ($mixedResult['result'] == true) {
                    $result = 'success';
                    CommonMailer::sendMailRecoverPassword($mixedResult['user'], null);
                } else {
                    $result = 'erreur';
                }
                $message = $mixedResult['message'];
                Yii::app()->user->setFlash($result, $message);
            }
        }$this->render('recoverPwd', array('model' => $model,));
    }

    /**
     * action to add a new profil to an user.
     */
    public function actionUpdateSubscribe()
    {
        $model = new User;
        if (isset(Yii::app()->user->id)) {
            $model = User::model()->findByPk(new MongoID(Yii::app()->user->id));
        }

        if (isset($_POST['User'])) {
            $profilSelected = array_filter($_POST['User']['profil']);
            $test = empty($profilSelected);
            if (!empty($profilSelected)) {
                if (in_array('clinicien', $profilSelected)) {
                    array_push($model->profil, 'clinicien');
                    $model->address = $_POST['User']['address'];
                    if ($model->save()) {
                        CommonMailer::sendConfirmationAdminProfilUser($model);
                        CommonMailer::sendMailInscriptionUser($model->email, $model->login, $model->prenom, $model->nom, $model->password);
                        Yii::app()->user->setState('profil', $model->profil);
                        Yii::app()->user->setFlash('succès', Yii::t('common', 'clinicianProfileCreated'));
                        $this->redirect(array('site/index'));
                    }
                } else {
                    if (in_array('neuropathologiste', $profilSelected)) {
                        if ($_POST['User']['centre'] == null || $_POST['User']['centre'] == "") {
                            $model->addError('centre', Yii::t('common', 'referenceCenterRequired'));
                            Yii::app()->user->setFlash('erreur', Yii::t('common', 'referenceCenterRequired'));
                        } else
                            $model->centre = $_POST['User']['centre'];
                    }
                    if (!$model->hasErrors()) {
                        CommonMailer::sendMailConfirmationProfilEmail($model, implode('', $profilSelected), $model->centre);

                        Yii::app()->user->setFlash('succès', Yii::t('common', 'askProfile') . implode("", $profilSelected) . Yii::t('common', 'askProfile1'));
                        $this->redirect(array('site/index'));
                    }
                }
            } else {
                $model->addError('profil', Yii::t('common', 'selectProfile'));
            }
        }
        $this->render('_updateSubscribeForm', array('model' => $model));
    }

    /**
     * Displays the subscribeProfil page. User can choose which profil he wants to subscribe.
     */
    public function actionSubscribeProfil()
    {
        $model = new User ();
        if (isset($_POST['clinicien'])) {
            $_SESSION['profil'] = $profil = "clinicien";
            $this->render('subscribe', array('model' => $model, 'profil' => $_SESSION['profil']));
        } else
        if (isset($_POST['neuropathologiste'])) {
            $_SESSION['profil'] = $profil = "neuropathologiste";
            $this->render('subscribe', array('model' => $model, 'profil' => $_SESSION['profil']));
        } else
        if (isset($_POST['geneticien'])) {
            $_SESSION['profil'] = $profil = "geneticien";
            $this->render('subscribe', array('model' => $model, 'profil' => $_SESSION['profil']));
        } else
        if (isset($_POST['chercheur'])) {
            $_SESSION['profil'] = $profil = "chercheur";
            $this->render('subscribe', array('model' => $model, 'profil' => $_SESSION['profil']));
        } else
            $this->render('subscribeProfil', array('model' => $model));
    }

    /**
     * action to subscribe a new user account.
     */
    public function actionSubscribe()
    {
        $model = new User();
        $model->setScenario('subscribe');
        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            strtoupper($model->nom);
            ucfirst($model->prenom);
            $model->telephone = str_replace(" ", "", $model->telephone);
            $profil = implode("", $model->profil);
            $userLogin = $model->getAllUsersByLogin($model);
            if (count($userLogin) > 0) {
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'loginExist'));
            } else {
                if ($profil == "clinicien") {
                    if ($model->validate()) {
                        if (empty($_POST['User']['address'])) {
                            $model->addError('address', Yii::t('common', 'addressRequired'));
                        } else {
                            if ($model->save()) {
                                CommonMailer::sendSubscribeUserMail($model, $profil);
                                CommonMailer::sendMailConfirmationProfilEmail($model, $profil, $_POST['User']['address']);
                                Yii::app()->user->setFlash('succès', Yii::t('common', 'success_register'));
                                $this->redirect(array('site/login'));
                            }
                        }
                    }
                }
                if ($profil == "neuropathologiste") {
                    if ($model->validate()) {
                        if (empty($_POST['User']['centre'])) {
                            $model->addError('centre', Yii::t('common', 'referenceCenterRequired'));
                        } else {
                            if ($model->save()) {
                                CommonMailer::sendSubscribeUserMail($model, $profil);
                                CommonMailer::sendMailConfirmationProfilEmail($model, $profil, $_POST['User']['centre']);
                                Yii::app()->user->setFlash('succès', Yii::t('common', 'success_register'));
                                $this->redirect(array('site/login'));
                            }
                        }
                    }
                }
                if ($profil == "geneticien" || $profil == "chercheur") {
                    if ($model->save()) {
                        CommonMailer::sendSubscribeUserMail($model, $profil);
                        CommonMailer::sendMailConfirmationProfilEmail($model, $profil, NULL);
                        Yii::app()->user->setFlash('succès', Yii::t('common', 'success_register'));
                        $this->redirect(array('site/login'));
                    }
                }
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'userNotSaved'));
            }
        }
        $this->render('subscribe', array('model' => $model));
    }

    /**
     * action to confirm new profil on mail validation.
     */
    public function actionConfirmUser()
    {
        if (isset($_GET['arg1']) && isset($_GET['arg2'])) {
            $model = User::model()->findByPk(new MongoId($_GET['arg1']));
            if ($model != null) {
                if (!in_array($_GET['arg2'], $model->profil)) {
                    if (!in_array(" ", $model->profil)) {
                        array_push($model->profil, $_GET['arg2']);
                    } else {
                        $model->profil = (array) $_GET['arg2'];
                    }
                    if (isset($_GET['arg2']) && isset($_GET['arg3'])) {
                        if ($_GET['arg2'] == "neuropathologiste") {
                            $model->centre = $_GET['arg3'];
                        }
                    }
                    if ($model->save()) {
                        CommonMailer::sendUserRegisterConfirmationMail($model, NULL);
                        Yii::app()->user->setState('profil', $model->profil);
                        Yii::app()->user->setFlash('succès', Yii::t('common', 'profile1') . $_GET['arg2'] . Yii::t('common', 'profile2'));
                    }
                } else {
                    Yii::app()->user->setFlash('erreur', Yii::t('common', 'profile1') . $_GET['arg2'] . Yii::t('common', 'profile3'));
                }
            } else {
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'userNotExist'));
            }
        } else {
            Yii::app()->user->setFlash('erreur', Yii::t('common', 'unvalidLink'));
        }
        $this->redirect(array('site/login'));
    }

    /**
     * action to refuse user on mail validation.
     */
    public function actionRefuseUser()
    {
        if (isset($_GET['arg1'])) {
            $model = User::model()->findByPk(new MongoId($_GET['arg1']));
            if ($model != null && $model->delete()) {
                CommonMailer::sendUserRegisterRefusedMail($model, $_GET['arg2']);
                Yii::app()->user->setFlash('succès', Yii::t('common', 'userProfile1') . $model->login . Yii::t('common', 'userProfile2') . Yii::t('common', $_GET['arg2']) . Yii::t('common', 'userProfile3'));
            } else {
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'userNotExist'));
            }
        } else {
            Yii::app()->user->setFlash('erreur', Yii::t('common', 'unvalidLink'));
        }
        $this->redirect(array('site/login'));
    }
}