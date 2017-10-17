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
        if (!isset($_GET['ajax'])) {
            if (isset($_SESSION['checkedIds'])) {
                foreach ($_SESSION['checkedIds'] as $ar) {
                    Yii::app()->user->setState($ar, 0);
                }
            }
        }
        if (isset($_GET['User'])) {
            $model->setAttributes($_GET['ReferenceCenter']);
        }
        if (isset($_GET['checkedIds']) && !empty($_GET['checkedIds'])) {
            CommonTools::chkIds($_GET['checkedIds']);
        }
        if (isset($_GET['uncheckedIds']) && !empty($_GET['uncheckedIds'])) {
            CommonTools::unckIds($_GET['uncheckedIds']);
        }
        if (isset($_POST['rechercher'])) {
            if (isset($_POST['ReferenceCenter_id'])) {
                if (isset($_SESSION['checkedIds'])) {
                    foreach ($_SESSION['checkedIds'] as $user_id) {
                        array_push($_POST['ReferenceCenter_id'], $user_id);
                    }
                }
                foreach ($_POST['ReferenceCenter_id'] as $key => $value) {
                    if ($this->loadModel($value) !== null) {
                        $this->loadModel($value)->delete();
                    }
                    Yii::app()->user->setFlash('succès', Yii::t('common', 'referencesDeleted'));
                }
            } else {
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'referencesNotDeleted'));
            }
        }
        $this->render('admin', array(
            'model' => $model
        ));
    }
    
    public function actionCreate() {
        $model = new ReferenceCenter;
        if (isset($_POST['ReferenceCenter'])) {
            $model->attributes = $_POST['ReferenceCenter'];
            if ($model->save()) {
                Yii::app()->user->setFlash('succès', Yii::t('common', 'referenceSaved'));
                $this->redirect(array('referenceCenter/admin'));
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
        if (isset($_POST['ReferenceCenter'])) {
            $model->attributes = $_POST['ReferenceCenter'];
            if ($model->save()) {
                Yii::app()->user->setFlash('succès', Yii::t('common', 'referenceUpdated'));
                $this->redirect(array('referenceCenter/admin'));
            } else {
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'referenceNotUpdated'));
            }
        }
        $this->render('update', array(
            'model' => $model,
        ));
    }

    public function loadModel($id) {
        $model = ReferenceCenter::model()->findByPk(new MongoId($id));
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
                Yii::app()->user->setFlash('succès', Yii::t('common', 'referenceDeleted'));
            } else {
                echo "<div class='flash-success'>" . Yii::t('common', 'referenceDeleted') . "</div>"; //for ajax
            }
        } catch (CDbException $e) {
            if (!isset($_GET['ajax'])) {
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'referenceNotDeleted'));
            } else {
                echo "<div class='flash-error'>" . Yii::t('common', 'referenceNotDeleted') . "</div>";
            } //for ajax
        }

// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }
}
