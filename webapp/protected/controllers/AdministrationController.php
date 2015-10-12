<?php

class AdministrationController extends Controller {

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
                    'index',
                ),
                'expression' => '$user->isAdmin()'
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * action par defaut pour afficher des infos sur l administration et le menu.
     */
    public function actionIndex() {

        $this->render('index');
    }

}
