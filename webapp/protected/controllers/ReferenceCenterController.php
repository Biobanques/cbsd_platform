<?php

class ReferenceCenterController extends Controller {

    /**
     * NB : boostrap theme need this column2 layout
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

    public function accessRules() {
        return array(
            array(
                'allow',
                'actions' => array(
                    'admin',
                    'create',
                    'update',
                    'delete'
                ),
                'expression' => '$user->isAdmin()'
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionAdmin() {
        $model = new ReferenceCenter('search');
        $model->unsetAttributes();
        if (isset($_GET['User']))
            $model->setAttributes($_GET['ReferenceCenter']);
        $this->render('admin', array(
            'model' => $model
        ));
    }
    
    public function actionCreate() {
        $model = new ReferenceCenter;
        if (isset($_POST['ReferenceCenter'])) {
            $model->attributes = $_POST['ReferenceCenter'];
            if ($model->save()) {
                Yii::app()->user->setFlash('succès', 'OK');
                $this->redirect(array('referenceCenter/admin'));
            } else {
                Yii::app()->user->setFlash('erreur', 'KO');
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
        if (isset($_POST['ReferenceCenter'])) {
            $model->attributes = $_POST['ReferenceCenter'];
            if ($model->save()) {
                Yii::app()->user->setFlash('succès', 'OK');
                $this->redirect(array('referenceCenter/admin'));
            }
        }
        $this->render('update', array(
            'model' => $model,
        ));
    }

    public function loadModel($id) {
        $model = ReferenceCenter::model()->findByPk(new MongoId($id));
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
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
                Yii::app()->user->setFlash('succès', Yii::t('common', 'OK'));
            } else {
                echo "<div class='flash-success'>" . Yii::t('common', 'OK') . "</div>"; //for ajax
            }
        } catch (CDbException $e) {
            if (!isset($_GET['ajax'])) {
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'KO'));
            } else {
                echo "<div class='flash-error'>" . Yii::t('common', 'KO') . "</div>";
            } //for ajax
        }

// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
}
