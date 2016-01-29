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
                    'admin',
                    'update'
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

    public function actionAdmin() {
        $model = new Droits('search');
        $model->unsetAttributes();
        if (isset($_GET['Droits']))
            $model->setAttributes($_GET['Droits']);
        $criteria = new EMongoCriteria();
        $criteriaClinique = new EMongoCriteria($criteria);
        $criteriaClinique->type = "clinique";
        $dataProviderClinique = new EMongoDocumentDataProvider('Droits');
        $dataProviderClinique->setCriteria($criteriaClinique);

        $criteriaNeuropath = new EMongoCriteria($criteria);
        $criteriaNeuropath->type = "neuropathologique";
        $dataProviderNeuropath = new EMongoDocumentDataProvider('Droits');
        $dataProviderNeuropath->setCriteria($criteriaNeuropath);

        $criteriaGene = new EMongoCriteria($criteria);
        $criteriaGene->type = "genetique";
        $dataProviderGene = new EMongoDocumentDataProvider('Droits');
        $dataProviderGene->setCriteria($criteriaGene);

        $this->render('admin', array(
            'model' => $model,
            'dataProviderClinique' => $dataProviderClinique,
            'dataProviderNeuropath' => $dataProviderNeuropath,
            'dataProviderGene' => $dataProviderGene
        ));
    }

    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        if (isset($_POST['Droits'])) {
            $model->attributes = $_POST['Droits'];
            if ($model->update()) {
                Yii::app()->user->setFlash('success', 'Le profil a bien été mis à jour.');
                $this->redirect(array('admin'));
            }
        }
        $this->render('update', array(
            'model' => $model,
        ));
    }

    public function loadModel($id) {
        $model = Droits::model()->findByPk(new MongoId($id));
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}
