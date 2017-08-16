<?php

class UserController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/menu_administration';

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
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('create', 'update', 'index', 'admin', 'view', 'delete', 'deleteMany'),
                'expression' => '$user->getActiveProfil() == "Administrateur"'
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new User;
        $model->setScenario('subscribe');
        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            $model->nom = strtoupper($model->nom);
            $model->prenom = ucfirst($model->prenom);
            $criteria = new EMongoCriteria();
            $criteria->login = $model->login = strtolower($model->prenom . "." . $model->nom);
            $userLogin = User::model()->findAll($criteria);
            if (count($userLogin) > 0) {
                $nbUserLogin = count($userLogin);
                $model->login = strtolower($model->prenom . "." . $model->nom . $nbUserLogin);
                while (User::model()->findByAttributes(array('login' => $model->login)) != null) {
                    $model->login = strtolower($model->prenom . "." . $model->nom . $nbUserLogin++);
                }
            }
            if ($model->save()) {
                Yii::app()->user->setFlash('succès', Yii::t('common', 'userSaved'));
                if (in_array("Administrateur", $model->profil)) {
                    $adminsUser = User::model()->getAllEmailsAdmin();
                    foreach ($adminsUser as $k => $v) {
                        if ($adminsUser[$k] == "bernardte90@gmail.com") {
                            unset($adminsUser[$k]);
                        }
                    }
                    foreach ($adminsUser as $admin) {
                        CommonMailer::sendSubscribeAdminToAdminsMail($admin, $model);
                    }
                }
                $this->redirect(array('view', 'id' => $model->_id));
            } else {
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'missingFields'));
            }
        }
        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            if ($model->save()) {
                Yii::app()->user->setFlash('succès', Yii::t('common', 'userProfile1') . $model->login . Yii::t('common', 'userProfile4'));
                $this->redirect(array('view', 'id' => $model->_id));
            }
        }
        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        try {
            $this->loadModel($id)->delete();
            if (!isset($_GET['ajax'])) {
                Yii::app()->user->setFlash('succès', Yii::t('common', 'userDeleted'));
            } else {
                echo "<div class='flash-success'>" . Yii::t('common', 'userDeleted') . "</div>"; //for ajax
            }
        } catch (CDbException $e) {
            if (!isset($_GET['ajax'])) {
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'userNotDeleted'));
            } else {
                echo "<div class='flash-error'>" . Yii::t('common', 'userNotDeleted') . "</div>";
            } //for ajax
        }

// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /* public function actionDeleteMany() {
      if (isset($_POST['deleteMany'])) {
      if (isset($_POST['User_id'])) {
      $criteria = new EMongoCriteria;
      $regex = '/^';
      foreach ($_POST['User_id'] as $login) {
      $regex.= $login . '$|^';
      }
      $regex .= '$/i';
      $criteria->addCond('login', '==', new MongoRegex($regex));
      $model = User::model()->findAll($criteria);
      print_r($model);
      }
      }
      } */

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new EMongoDocumentDataProvider('User');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new User('search');
        $model->unsetAttributes();
        if (isset($_GET['User'])) {
            $model->setAttributes($_GET['User']);
        }
        if (isset($_POST['rechercher'])) {
            if (isset($_POST['User_id'])) {
                foreach($_POST['User_id'] as $key => $value) {
                    $this->loadModel($value)->delete();
                    Yii::app()->user->setFlash('succès', Yii::t('user', 'usersDeleted'));
                }
            } else {
                Yii::app()->user->setFlash('erreur', Yii::t('user', 'usersNotDeleted'));
            }
        }
        $this->render('admin', array(
            'model' => $model
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = User::model()->findByPk(new MongoId($id));
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionValidate($id) {
        $model = $this->loadModel($id);
        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);
        $model->statut = "actif";
        if ($model->update()) {
            CommonMailer::sendUserRegisterConfirmationMail($model);
            Yii::app()->user->setFlash('succès', Yii::t('common', 'userProfile1bis') . $model->_id . ' (' . $model->prenom . ' ' . $model->nom . ')' . Yii::t('common', 'userProfile5'));
        } else {
            Yii::app()->user->setFlash('erreur', Yii::t('common', 'userProfile1bis') . $model->_id . ' (' . $model->prenom . ' ' . $model->nom . ')' . Yii::t('common', 'userProfile6'));
        }
        $this->redirect(array(
            'admin',
        ));
    }

    public function actionRefuseRegistration($id) {
        $model = $this->loadModel($id);
        CommonMailer::sendUserRegisterRefusedMail($model);
        $this->redirect(array(
            'desactivate', 'id' => $id,
        ));
    }

    public function actionDesactivate($id) {
        $model = $this->loadModel($id);
        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model);
        $model->statut = "inactif";
        if ($model->update()) {
            Yii::app()->user->setFlash('succès', Yii::t('common', 'userProfile1bis') . $model->_id . ' (' . $model->prenom . ' ' . $model->nom . ')' . Yii::t('common', 'userProfile7'));
        } else {
            Yii::app()->user->setFlash('erreur', Yii::t('common', 'userProfile1bis') . $model->_id . ' (' . $model->prenom . ' ' . $model->nom . ')' . Yii::t('common', 'userProfile8'));
        }
        $this->redirect(array(
            'admin',
        ));
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
