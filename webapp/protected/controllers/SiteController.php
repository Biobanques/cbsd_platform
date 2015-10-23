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
                    'setActiveProfil'
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
            if ($model->login() && in_array($model->profil, Yii::app()->user->getState('profil'))) {
                Yii::app()->user->setState('activeProfil', $model->profil);
                $this->redirect(Yii::app()->user->returnUrl);
            } else if ($model->login() && (!in_array($model->profil, Yii::app()->user->getState('profil')))) {
                Yii::app()->user->setFlash('error', 'Il n\'y a pas de profil associé à cet utilisateur.');
            } else
                Yii::app()->user->setFlash('error', 'Le nom d\'utilisateur ou le mot de passe est incorrect.');
        }

        $action = "";
        if (isset(Yii::app()->user->id))
            $action = Yii::app()->createUrl('site/updateSubscribe');
        else
            $action = Yii::app()->createUrl('site/subscribe');

        // display the login form
        $this->render('login', array('model' => $model, 'action' => $action));
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
                    CommonMailer::sendMailRecoverPassword($mixedResult['user']);
                } else {
                    $result = 'error';
                }
                $message = $mixedResult['message'];
                Yii::app()->user->setFlash($result, $message);
            }
        }$this->render('recoverPwd', array('model' => $model,));
    }

    public function actionUpdateSubscribe() {
        $model = new User ();
        if (isset(Yii::app()->user->id)) {
            $model = User::model()->findByPk(new MongoID(Yii::app()->user->id));
            if (isset($_POST ['User'])) {
                $model->attributes = $_POST ['User'];
                if ($model->update()) {
                    Yii::app()->user->setFlash('success', 'Le profil a bien été ajouté.');
                    $this->redirect(array('site/patient'));
                } else
                    Yii::app()->user->setFlash('error', 'Bienvenue sur CBSDForms !');
            }
            if (isset($_POST['clinicien']))
                $_SESSION['profil'] = $profil = "clinicien";
            if (isset($_POST['neuropathologiste']))
                $_SESSION['profil'] = $profil = "neuropathologiste";
            if (isset($_POST['geneticien']))
                $_SESSION['profil'] = $profil = "geneticien";
            if (isset($_POST['chercheur']))
                $_SESSION['profil'] = $profil = "chercheur";
            $this->render('subscribe', array('model' => $model, 'profil' => $_SESSION['profil']));
        }
    }

    /**
     * action to subscribe a new user account.
     */
    public function actionSubscribe() {
        if (isset($_POST['clinicien']))
            $_SESSION['profil'] = $profil = "clinicien";
        if (isset($_POST['neuropathologiste']))
            $_SESSION['profil'] = $profil = "neuropathologiste";
        if (isset($_POST['geneticien']))
            $_SESSION['profil'] = $profil = "geneticien";
        if (isset($_POST['chercheur']))
            $_SESSION['profil'] = $profil = "chercheur";
        $model = new User ();
        if (isset($_POST ['User'])) {
            $model->attributes = $_POST ['User'];
            $model->statut = "inactif";
            if ($model->save()) {
                if ($model->profil == array("clinicien")) {
                    $model->statut = "actif";
                    $model->update();
                    if ($model->update()) {
                        Yii::app()->user->setFlash('success', 'Bienvenue sur CBSDForms !');
                        $this->redirect(array('site/index'));
                    }
                }
                Yii::app()->user->setFlash('success', Yii::t('common', 'success_register'));
                $this->redirect(array('site/index'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('common', 'error_register'));
                $profil = $_SESSION['profil'];
            }
        }
        $this->render('subscribe', array('model' => $model, 'profil' => $_SESSION['profil']));
    }

}
