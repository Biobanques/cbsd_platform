<?php

class ProjectController extends Controller {

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
                'actions' => array('admin', 'import', 'delete'),
                'expression' => '$user->getActiveProfil() == "Administrateur"'
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        try {
            $filePath = CommonProperties::$EXPORT_CSV_PATH;
            if (substr($filePath, -1) != '/') {
                $filePath.='/';
            }
            chdir(Yii::app()->basePath . "/" . $filePath);
            unlink($this->loadModel($id)->file);
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

    /**
     * Lists all models.
     */
    /* public function actionIndex() {
      $dataProvider = new EMongoDocumentDataProvider('User');
      $this->render('index', array(
      'dataProvider' => $dataProvider,
      ));
      } */

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Project('search');
        $model->unsetAttributes();
        if (isset($_GET['Project'])) {
            $model->setAttributes($_GET['Project']);
        }
        if (isset($_POST['rechercher'])) {
            if (isset($_POST['Project_id'])) {
                foreach($_POST['Project_id'] as $key => $value) {
                    $this->loadModelFileImport($value)->delete();
                    Yii::app()->user->setFlash('succès', Yii::t('common', 'projectsDeleted'));
                }
            } else {
                Yii::app()->user->setFlash('erreur', Yii::t('common', 'projectsNotDeleted'));
            }
        }
        $this->render('admin', array(
            'model' => $model
        ));
    }

    public function actionImport($project_file) {
        $filePath = CommonProperties::$EXPORT_CSV_PATH;
        if (substr($filePath, -1) != '/') {
            $filePath.='/';
        }
        chdir(Yii::app()->basePath . "/" . $filePath);
        if (file_exists($project_file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($project_file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($project_file));
            ob_clean();
            flush();
            readfile($project_file);
        } else {
            Yii::app()->user->setFlash('erreur', 'Le projet n\'a pas été supprimé.');
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Project::model()->findByPk(new MongoId($id));
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}
