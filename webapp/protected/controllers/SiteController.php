<?php

class SiteController extends Controller {

    /**
     * NB : boostrap theme need this column2 layout
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     *
     * @return array access control rules
     */
    public function accessRules() {
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
                ),
                'users' => array(
                    '*'
                )
            ),
            array(
                'allow',
                'actions' => array(
                    'patient',
                    'affichepatient',
                    'setActiveProfil',
                    'updateSubscribe'
                ),
                'users' => array(
                    '@'
                )
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
    public function actionIndex() {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'

        $this->render('index');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
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
        $this->render('patient', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                $user = User::model()->findByPk(new MongoID(Yii::app()->user->id));
                if (in_array(Yii::app()->user->getActiveProfil(), $user->profil))
                    $this->redirect(array('site/patient'));
                else
                    $this->redirect(array('site/logoutInactifProfil'));
            } else
                Yii::app()->user->setFlash('error', 'Le nom d\'utilisateur ou le mot de passe est incorrect.');
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Displays the loginProfil page
     */
    public function actionLoginProfil() {
        $user = User::model()->findByPk(new MongoID(Yii::app()->user->id));
        // display the login form
        if (isset($_POST['profil'])) {
            $selected_radio = $_POST['profil'];
            if ($selected_radio == "administrateur") {
                Yii::app()->user->setActifProfil($selected_radio);
            } else
            if ($selected_radio == "clinicien") {
                Yii::app()->user->setActifProfil($selected_radio);
            } else
            if ($selected_radio == "neuropathologiste") {
                Yii::app()->user->setActifProfil($selected_radio);
            } else
            if ($selected_radio == "geneticien") {
                Yii::app()->user->setActifProfil($selected_radio);
            } else
            if ($selected_radio == "chercheur") {
                Yii::app()->user->setActifProfil($selected_radio);
            }
            if (in_array($selected_radio, $user->statut)) {
                Yii::app()->user->setFlash('success', 'Vous êtes connecté sous le profil ' . Yii::app()->user->getActiveProfil() . '.');
                $this->redirect(Yii::app()->user->returnUrl);
            } else {
                Yii::app()->user->setFlash('error', 'Votre profil n\'a pas encore été activé. Veuillez contacter l\'administrateur.');
                $this->render('loginProfil');
            }
        } else
            $this->render('loginProfil');
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    /**
     * Logs out the current user if active profil is "inactif" and redirect to homepage.
     */
    public function actionLogoutInactifProfil() {
        Yii::app()->user->logout();
        // the current session, we need to create a dummy session
        Yii::app()->session->open();
        Yii::app()->user->setFlash('error', 'Le profil n\'est pas encore activé. Veuillez contacter l\'administrateur.');
        $this->redirect(Yii::app()->homeUrl);
    }

// username and password are required
    // rememberMe needs to be a boolean
    /**
     * display the recover password page
     */
    public function actionRecoverPwd() {
        $model = new RecoverPwdForm();
        $result = '';
        if (isset($_POST['RecoverPwdForm'])) {
            $model->attributes = $_POST['RecoverPwdForm'];
            if ($model->validate()) {
                $mixedResult = $model->validateFields();
                if ($mixedResult['result'] == true) {
                    $result = 'success';
                    CommonMailer::sendMailRecoverPassword($mixedResult['user']);
                } else {
                    $result = 'error';
                }
                $message = $mixedResult['message'];
                Yii::app()->user->setFlash($result, $message);
            }
        }$this->render('recoverPwd', array('model' => $model,));
    }

    /**
     * action to add a new profil to an user.
     */
    public function actionUpdateSubscribe() {
        $model = new User;
        if (isset(Yii::app()->user->id)) {
            $model = User::model()->findByPk(new MongoID(Yii::app()->user->id));
            // get current user profils
            $profil = $model->profil;
        }
        if (isset($_POST ['User'])) {
            foreach ($_POST['User'] as $key => $value) {
                if ($key == "address" && $value != "") {
                    $model->$key = $value;
                }
                if ($key == "centre" && $value != "") {
                    $model->$key = $value;
                }
            }
            foreach ($_POST['User'] as $key => $value) {
                if ($key == "profil") {
                    if (in_array("clinicien", $value)) {
                        array_push($model->$key, implode("", $value));
                        if ($model->save()) {
                            //CommonMailer::sendSubscribeUserMail($model);
                            Yii::app()->user->setFlash('success', 'Le profil Clinicien a bien été crée.');
                            $this->render('index', array('model' => $model));
                        }
                    } else {
                        if ($model->save()) {
                            //CommonMailer::sendSubscribeAdminMail($model);
                            //CommonMailer::sendSubscribeUserMail($model);
                            Yii::app()->user->setFlash('success', 'La demande pour le profil ' . implode("", $value) . ' a bien été prise en compte. Vouz recevrez un mail de confirmation');
                            $this->render('index', array('model' => $model));
                        }
                    }
                } else {
                    $model->$key = $value;
                }
            }
        }
        $this->render('_updateSubscribeForm', array('model' => $model));
    }

    /**
     * Displays the subscribeProfil page. User can choose which profil he wants to subscribe.
     */
    public function actionSubscribeProfil() {
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
    public function actionSubscribe() {
        $model = new User ();
        if (isset($_POST ['User'])) {
            $model->attributes = $_POST ['User'];
            if ($model->profil != array("clinicien"))
                $model->profil = array(" ");

            $criteria = new EMongoCriteria();
            $criteria->login = $model->login;
            $userLogin = User::model()->findAll($criteria);
            if (count($userLogin) > 0) {
                Yii::app()->user->setFlash('error', 'Le login a déjà été utilisé. Veuillez choisir un login différent.');
                $this->render('subscribe', array('model' => $model));
            } else {
                if ($model->save()) {
                    if ($model->profil == array("clinicien")) {
                        Yii::app()->user->setFlash('success', 'Bienvenue sur CBSDPlatform !');
                        $this->redirect(array('site/index'));
                    }
                    //CommonMailer::sendSubscribeAdminMail($model);
                    //CommonMailer::sendSubscribeUserMail($model);
                    Yii::app()->user->setFlash('success', Yii::t('common', 'success_register'));
                    $this->redirect(array('site/index'));
                } else {
                    Yii::app()->user->setFlash('error', 'L\'utilisateur n\'a pas été enregistré.');
                }
            }
        }
        $this->render('subscribe', array('model' => $model));
    }

}
