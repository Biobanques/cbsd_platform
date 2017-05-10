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
                    'create'
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

    public function loadModel($id) {
        $model = Droits::model()->findByPk(new MongoId($id));
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }
}
