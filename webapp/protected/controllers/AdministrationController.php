<?php

class AdministrationController extends Controller {

    /**
     * NB : boostrap theme need this column2 layout
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/menu_administration';

    public function accessRules() {
        return array(
            array(
                'allow',
                'actions' => array(
                    'index',
                ),
                'users' => array(
                    '@'
                )
            ),
        );
    }

    /**
     * action par defaut pour afficher des infos sur l administration et le menu.
     */
    public function actionIndex() {

        $this->render('index');
    }
   
        /**
     * gestion des formulaires
     */
    public function actionFormulaires() {
        $model = new Questionnaire('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Questionnaire']))
            $model->attributes = $_GET['Questionnaire'];

        $this->render('formulaires', array(
            'model' => $model,
        ));
    }
    
       /**
     * Affiche un formulaire ,en  lecture uniquement
     * @param $id the ID of the model to be displayed
     */
    public function actionView($id) {
         $model = Questionnaire::model()->findByPk(new MongoID($id));
        $this->render('view_questionnaire', array(
            'model' => $model,
        ));
    }

}
