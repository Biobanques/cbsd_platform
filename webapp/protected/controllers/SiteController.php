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
                    'captcha', 'recoverPwd',
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
                    'affichepatient'
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
     * Declares class-based actions.
     */
    public function actions() {
        $captcha = array(
            'class' => 'CaptchaExtendedAction',
            'mode' => CaptchaExtendedAction::MODE_MATH,
        );
        //ajout de fixed value si mode de dev
        if (CommonTools::isInDevMode()) {
            $captchaplus = array('fixedVerifyCode' => "bernard");
            $captcha = array_merge($captcha, $captchaplus);
        }
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => $captcha,
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction'
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
        $model = new Patient;
        if (isset($_POST['Patient'])) {
            $model->attributes = $_POST['Patient'];
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
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
            else
                Yii::app()->user->setFlash('error', 'Une erreur est survenue, merci de vérifier vos identifiants');
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
     * action to subscribe a new user account.
     */
    public function actionSubscribe() {
        $model = new User ();
        if (isset($_POST ['User'])) {
            $model->attributes = $_POST ['User'];
            $model->inactif = 1;
            if ($model->save()) {
                if ($model->profil == 0) {
                    $model->inactif = 0;
                    $model->update();
                    if ($model->update()) {
                        Yii::app()->user->setFlash('success', 'Bienvenue sur CBSDForms !');
                        $this->redirect(array('site/index'));
                    }
                }
                CommonMailer::sendSubscribeAdminMail($model);
                CommonMailer::sendSubscribeUserMail($model);
                Yii::app()->user->setFlash('success', Yii::t('common', 'success_register'));
                $this->redirect(array('site/index'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('common', 'error_register'));
            }
        }
        $this->render('subscribe', array(
            'model' => $model
                )
        );
    }

}
