<?php
/* @var $this Controller */
if (!defined('Base'))
    define('Base', Yii::app()->request->baseUrl);
if (!defined('BaseTheme'))
    define('BaseTheme', Yii::app()->theme->baseUrl);
if (Yii::app()->urlManager->parseUrl(Yii::app()->request) == "questionnaire/update" || Yii::app()->urlManager->parseUrl(Yii::app()->request) == "answer/update") {
    $criteria = new EMongoCriteria;
    $criteria->_id = new MongoId($_GET['id']);
    if (Yii::app()->controller->id == "questionnaire") {
        $model = Questionnaire::model()->find($criteria);
    } elseif (Yii::app()->controller->id == "answer") {
        $model = Answer::model()->find($criteria);
    }
    if ($model->type === "clinique") {
        // Système de calcul de score IADL et ADL
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/iadl.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/adl.js', CClientScript::POS_END);
    }
    if ($model->type === "neuropathologique") {
        // Système de calcul de score pour le Stade de Braak
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/stade_de_braak.js', CClientScript::POS_END);
    }
    if ($model->type === "genetique") {
        // Ajout de gène dans le formulaire génétique
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gene.js', CClientScript::POS_END);
    }
}
if (Yii::app()->controller->id == "user") {
    // Afficher/cacher le champ "Adresse" ou "Centre de référence" lors de la création et la mise à jour de l'utilisateur
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/updateUserAdmin.js', CClientScript::POS_END);
}
if (Yii::app()->controller->id == "site" && Yii::app()->controller->action->id == "updatesubscribe") {
    // Afficher/cacher le champ "Adresse" ou "Centre de référence" lors de l'ajout d'un nouveau profil
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/createUserSubscribe.js', CClientScript::POS_END);
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/styles.css" />

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/questionnaire.css" />

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

        <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
            <title><?php echo CHtml::encode($this->pageTitle); ?></title>

            <?php Yii::app()->bootstrap->register(); ?>
    </head>

    <?php
    if (Yii::app()->urlManager->parseUrl(Yii::app()->request) != "rechercheFiche/viewOnePage" && Yii::app()->urlManager->parseUrl(Yii::app()->request) != "rechercheFiche/view") {
        $menuItems = array(
            array('label' => Yii::t('common', 'accueil'), 'url' => array('/site/index'), 'visible' => !Yii::app()->user->isGuest && Yii::app()->controller->action->id != "loginProfil"),
            array('label' => 'Saisir une fiche patient', 'url' => array('/site/patient'), 'visible' => !Yii::app()->user->isGuest && Yii::app()->controller->action->id != "loginProfil"),
            array('label' => 'Recherche', 'url' => array('/rechercheFiche/admin'), 'visible' => !Yii::app()->user->isGuest && Yii::app()->user->getActiveProfil() != "clinicien"),
            array('label' => 'Administration', 'url' => array('/administration/index'), 'visible' => Yii::app()->user->isAdmin() && Yii::app()->user->getActiveProfil() == "administrateur" && Yii::app()->controller->action->id != "loginProfil"),
            array('label' => Yii::t('common', 'seconnecter'), 'url' => array('/site/login'), 'visible' => Yii::app()->user->isGuest),
            array('label' => Yii::t('common', 'sedeconnecter') . ' (' . Yii::app()->user->name . ')', 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest),
            array('label' => 'Accédez en tant que : ', 'url' => '', 'visible' => !Yii::app()->user->isGuest));
        if (!Yii::app()->user->isGuest)
            $menuItems[] = array(
                'template' => GetProfil::getHTML(),
            );



        $this->widget('bootstrap.widgets.TbNavbar', array(
            'brandUrl' => (!Yii::app()->user->isGuest) ? array('/site/index') : "",
            'items' => array(
                array(
                    'class' => 'bootstrap.widgets.TbMenu',
                    'items' => $menuItems
                )
            )
        ));
    }
    ?>

    <body>
        <div class="container" id="page">

            <?php
            $this->widget('bootstrap.widgets.TbAlert', array(
                'block' => true,
                'fade' => true,
                'closeText' => '&times;', // false equals no close link
                'events' => array(),
                'htmlOptions' => array(),
                'alerts' => array(// configurations per alert type
                    // success, info, warning, error or danger
                    'success' => array('closeText' => '&times;'),
                    'info', // you don't need to specify full config
                    'warning' => array('block' => false, 'closeText' => false),
                    'error' => array('block' => false)
                ),
            ));
            ?>
            <?php echo $content; ?>

            <div class="clear"></div>
            <div style="height:100px;"/>
            <nav class="navbar navbar-default navbar-fixed-bottom" role="navigation">
                <div id="footer">
                    <div class="container">
                        <div class="row">
                            <?php echo CHtml::image(Base . '/images/LOGO FA.jpg', 'France Alzheimer', array('class' => 'logo')); ?>
                            <?php echo CHtml::image(Base . '/images/Logo-ARSEP-2015.png', 'Arsep Fondation', array('class' => 'logo')); ?>
                            <?php echo CHtml::image(Base . '/images/logo FP.jpg', 'France Parkinson', array('class' => 'logo')); ?>
                            <?php echo CHtml::image(Base . '/images/logo gie final 10-05-07.jpg', 'GIE Neuro-CEB', array('class' => 'logo')); ?>
                            <?php echo CHtml::image(Base . '/images/logo_CSC_quadri.jpg', 'CSC', array('class' => 'logo')); ?>
                            <?php echo CHtml::image(Base . '/images/logobb.png', 'Biobanques', array('class' => 'logo')); ?>
                            <?php echo CHtml::image(Base . '/images/logo_inserm.jpg', 'Inserm', array('class' => 'logo')); ?>
                        </div>
                        Copyright &copy; <?php echo date('Y'); ?> by Biobanques. Version 0.5.<br/>
                        All Rights Reserved.
                    </div>
                </div><!-- footer -->
            </nav>
        </div><!-- page -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </body>
</html>