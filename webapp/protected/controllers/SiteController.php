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
                $this->redirect(array('site/patient'));
            } else
                Yii::app()->user->setFlash('error', 'Le nom d\'utilisateur ou le mot de passe est incorrect.');
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
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
                    CommonMailer::sendMailRecoverPassword($mixedResult['user'], null);
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
                $profilSelected = implode((array) $value);
                if ($key == "profil") {
                    if (in_array("clinicien", $value)) {
                        array_push($model->$key, implode("", $value));
                        if ($model->save()) {
                            Yii::app()->user->setState('profil', $model->profil);
                            Yii::app()->user->setFlash('success', 'Le profil Clinicien a bien été crée.');
                            $this->render('index', array('model' => $model));
                        }
                    } else {
                        $complement = NULL;
                        if ($profilSelected == "clinicien") {
                            $complement = $model->address;
                        }
                        if ($profilSelected == "neuropathologiste") {
                            $complement = $model->centre;
                        }
                        CommonMailer::sendMailConfirmationProfilEmail($model->email, $model->prenom, $model->nom, $model->_id, $profilSelected, $complement);
                        Yii::app()->user->setFlash('success', 'La demande pour le profil ' . implode("", $value) . ' a bien été prise en compte. Vouz recevrez un mail de confirmation');
                        $this->render('index', array('model' => $model));
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
            $profil = implode("", $model->profil);
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
                        CommonMailer::sendSubscribeAdminMail($model, NULL);
                        CommonMailer::sendMailInscriptionUser($model->email, $model->login, $model->prenom, $model->nom, $model->password, NULL);
                        Yii::app()->user->setFlash('success', 'Bienvenue sur CBSDPlatform !');
                        $this->redirect(array('site/index'));
                    }
                    $complement = NULL;
                    if ($model->profil == array("neuropathologiste")) {
                        $complement = $model->centre;
                    }
                    CommonMailer::sendMailConfirmationProfilEmail($model->email, $model->prenom, $model->nom, $model->_id, $profil, $complement);
                    Yii::app()->user->setFlash('success', Yii::t('common', 'success_register'));
                    $this->redirect(array('site/index'));
                } else {
                    Yii::app()->user->setFlash('error', 'L\'utilisateur n\'a pas été enregistré.');
                }
            }
        }
        $this->render('subscribe', array('model' => $model));
    }

    /**
     * action to confirm new profil on mail validation.
     */
    public function actionConfirmProfil() {
        $model = User::model()->findByPk(new MongoId($_GET['arg1']));
        if (!in_array($_GET['arg2'], $model->profil)) {
            if (!in_array(" ", $model->profil))
                array_push($model->profil, $_GET['arg2']);
            else
                $model->profil = (array)$_GET['arg2'];
            if (isset($_GET['arg2']) && isset($_GET['arg3'])) {
                if ($_GET['arg2'] == "clinicien") {
                    $model->address = $_GET['arg3'];
                }
                if ($_GET['arg2'] == "neuropathologiste") {
                    $model->centre = $_GET['arg3'];
                }
            }
            if ($model->save()) {
                CommonMailer::sendSubscribeAdminMail($model, NULL);
                Yii::app()->user->setState('profil', $model->profil);
                Yii::app()->user->setFlash('success', 'Le profil ' . $_GET['arg2'] . ' a bien été ajouté.');
                $this->redirect(array('site/index'));
            }
        } else {
            Yii::app()->user->setFlash('error', 'Le profil ' . $_GET['arg2'] . ' a déjà été ajouté.');
            $this->redirect(array('site/index'));
        }
    }

}