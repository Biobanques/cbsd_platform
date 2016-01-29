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
                'actions' => array('create', 'update', 'index', 'admin', 'view', 'delete'),
                'expression' => '$user->isAdmin()'
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
        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            $criteria = new EMongoCriteria();
            $criteria->login = $model->login;
            $userLogin = User::model()->findAll($criteria);
            if (count($userLogin) > 0) {
                Yii::app()->user->setFlash('error', 'Le login a déjà été utilisé. Veuillez choisir un login différent.');
                $this->render('create', array('model' => $model));
            } else
            if ($model->save()) {
                Yii::app()->user->setFlash('success', 'L\'utilisateur a été enregistré avec succès.');
                $this->redirect(array('view', 'id' => $model->_id));
            } else {
                Yii::app()->user->setFlash('error', "Veuillez renseigner tous les champs obligatoires.");
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
                Yii::app()->user->setFlash('success', 'L\'utilisateur ' . $model->login . ' a été mise à jour.');
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
                Yii::app()->user->setFlash('success', "L'utilisateur a bien été supprimé.");
            } else {
                echo "<div class='alert in alert-block fade alert-success'>L'utilisateur a bien été supprimé.</div>"; //for ajax
            }
        } catch (CDbException $e) {
            if (!isset($_GET['ajax'])) {
                Yii::app()->user->setFlash('error', "L'utilisateur n'a pas été supprimé. Un problème est apparu.");
            } else {
                echo "<div class='alert in fade alert-error'>L'utilisateur n'a pas été supprimé. Un problème est apparu.</div>";
            } //for ajax
        }

// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

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
        if (isset($_GET['User']))
            $model->setAttributes($_GET['User']);

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
            Yii::app()->user->setFlash('success', 'L\'utilisateur n°' . $model->_id . ' (' . $model->prenom . ' ' . $model->nom . ') a bien été validé.');
        } else {
            Yii::app()->user->setFlash('error', 'L\'utilisateur n°' . $model->_id . ' (' . $model->prenom . ' ' . $model->nom . ') n\'a pas pu être validé. Consultez les logs pour plus de détails.');
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
            Yii::app()->user->setFlash('success', 'L\'utilisateur n°' . $model->_id . ' (' . $model->prenom . ' ' . $model->nom . ') a bien été désactivé.');
        } else {
            Yii::app()->user->setFlash('error', 'L\'utilisateur n°' . $model->_id . ' (' . $model->prenom . ' ' . $model->nom . ') n\'a pas pu être désactivé. Consultez les logs pour plus de détails.');
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
