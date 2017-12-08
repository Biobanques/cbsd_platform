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
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/thal.js', CClientScript::POS_END);
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

        <!-- blueprint CSS framework -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/styles.css" />

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
        <!--[if lt IE 8]>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
        <![endif]-->

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/custom.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/breadcrumbs.css" />
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
            <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/questionnaire.css" />

            <!-- use the link below to test cdn instead of local lib -->
            <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/font-awesome-4.6.3/css/font-awesome.min.css" />

            <!-- use bootstrap -->
            <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap-3.3.7-dist/css/bootstrap.min.css" />

            <!-- use DateRangePicker http://www.daterangepicker.com/ -->
            <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

            <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.uix.multiselect.css" />
            <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/common.css" />

            <title><?php echo CHtml::encode($this->pageTitle); ?></title>

            <?php
            Yii::app()->clientScript->registerCoreScript('jquery');
            Yii::app()->clientScript->registerCoreScript('jquery.ui');
            ?>
    </head>

    <body>
        <div class="container">
            <nav class="navbar navbar-default">
                <div class="container-fluid"> <!-- Brand and toggle get grouped for better mobile display --> 
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false"> 
                            <span class="sr-only">Toggle navigation</span> 
                            <span class="icon-bar"></span> 
                            <span class="icon-bar"></span> 
                            <span class="icon-bar"></span> 
                        </button>
                        <a class="navbar-brand" href="<?php echo Yii::app()->createUrl('site/index'); ?>"> <span class="stylelogo">CBSDPlatform</span> </a>
                    </div> <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1"> 
                        <ul class="nav navbar-nav">
                            <?php if (!Yii::app()->user->isGuest && Yii::app()->controller->action->id != "loginProfil") { ?>
                                <li><a href="<?php echo Yii::app()->createUrl('site/index'); ?>"><i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('navbar', 'accueil'); ?></a></li>
                            <?php } ?>
                            <?php if (Yii::app()->user->isAuthorizedViewPatientNavbar() && Yii::app()->controller->action->id != "loginProfil") { ?>
                                <li><a href="<?php echo Yii::app()->createUrl('site/patient'); ?>"><?php echo Yii::t('navbar', 'seizeForm'); ?></a></li>
                            <?php } ?>
                            <?php if (!Yii::app()->user->isGuest && Yii::app()->user->getActiveProfil() != "Clinicien" && Yii::app()->user->getActiveProfil() != "Chercheur") { ?>
                                <li class="dropdown">
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo (Yii::app()->user->getActiveProfil() != "Administrateur de projet") ? Yii::t('navbar', 'searchForm') : Yii::t('navbar', 'projectManager'); ?>
                                        <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo Yii::app()->createUrl('rechercheFiche/individualCases'); ?>"><?php echo Yii::t('common', 'individualSelection'); ?></a></li>
                                        <li><a href="<?php echo Yii::app()->createUrl('rechercheFiche/admin'); ?>"><?php echo "Requête"; ?></a></li>
                                    </ul>
                                </li>
                            <?php } ?>
                            <?php if (Yii::app()->user->isAdmin() && Yii::app()->controller->action->id != "loginProfil") { ?>
                                <li><a href="<?php echo Yii::app()->createUrl('administration/index'); ?>"><?php echo Yii::t('navbar', 'administration'); ?></a></li>
                            <?php } ?>
                            <?php if (!Yii::app()->user->isGuest && Yii::app()->controller->action->id != "loginProfil") { ?>
                                <li><a><?php echo Yii::t('navbar', 'accessProfil'); ?></a></li>
                                <li><?php echo GetProfil::getHTML(); ?></li>
                            <?php } ?>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <?php if (Yii::app()->user->isGuest) { ?>
                                <li><a href="<?php echo Yii::app()->createUrl('site/login'); ?>"><?php echo Yii::t('navbar', 'login'); ?></a></li>
                            <?php } ?>
                            <?php if (!Yii::app()->user->isGuest) { ?>
                                <li><a href="<?php echo Yii::app()->createUrl('site/logout'); ?>"><?php echo Yii::t('navbar', 'logout') . ' (' . Yii::app()->user->name . ')'; ?></a></li>
                            <?php } ?>
                        </ul>    
                    </div>
                </div>
            </nav>

            <div style="float:right; margin-top:10px; padding-right:20px; padding-top:20px;">
                <div >
                    <?php
                    /**
                     * Affichage des liens de traduction en gardant le couple controlleur/action et les parametres d'origine.
                     */
                    $controler = Yii::app()->getController()->getId();
                    $action = Yii::app()->getController()->getAction()->getId();
                    if ($controler == "admin") {
                        $controler = "auditTrail";
                    }
                    echo CHtml::link(
                            CHtml::image(Yii::app()->request->baseUrl . '/images/fr.png'), Yii::app()->createUrl("$controler/$action", array_merge($_GET, array('lang' => "fr"))
                            )
//                        ,                      $htmlOptions
                    );
                    echo CHtml::link(
                            CHtml::image(Yii::app()->request->baseUrl . '/images/gb.png'), Yii::app()->createUrl("$controler/$action", array_merge($_GET, array('lang' => "en")))
                            , array('style' => "padding-left: 10px;")
                    );
                    ?>
                </div>
            </div>

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
            <section class="main-body">
                <div class="container-fluid" style="height:70%; background-color: white; padding: 0px">
                    <div id="flashMessages">
                        <?php
                        $flashMessages = Yii::app()->user->getFlashes();
                        if ($flashMessages) {
                            foreach ($flashMessages as $key => $message) {
                                if ($key == "erreur") {
                                    $key = "error";
                                } elseif ($key == "succès") {
                                    $key = "success";
                                }
                                echo '<br><div class="flash-' . $key . '">' . $message . "</div>";
                            }
                        }
                        ?>
                    </div>
                    <?php echo $content; ?>
                </div>
            </section>

            <div class="clear"></div>
            <div style="height:200px;"></div>
            <nav class="navbar navbar-default navbar-fixed-bottom">
                <div id="footer">
                    <div class="container">
                        <div class="row">
                            <?php echo CHtml::link(CHtml::image(Base . '/images/ibisa.png', 'IBISA', array('height' => 70)), 'https://www.ibisa.net/'); ?>
                            <?php echo CHtml::link(CHtml::image(Base . '/images/lecma.jpg', 'Lecma Fondation', array('class' => 'logo')), 'https://www.vaincrealzheimer.org/'); ?>
                            <?php echo CHtml::link(CHtml::image(Base . '/images/Logo-ARSEP-2015.png', 'Arsep Fondation', array('class' => 'logo')), 'https://www.arsep.org/'); ?>
                            <?php echo CHtml::link(CHtml::image(Base . '/images/logo_FP.jpg', 'France Parkinson', array('class' => 'logo')), 'http://www.franceparkinson.fr/'); ?>
                            <?php echo CHtml::link(CHtml::image(Base . '/images/logo_gie_final_10-05-07.jpg', 'GIE Neuro-CEB', array('class' => 'logo')), 'http://www.neuroceb.org/'); ?>
                            <?php echo CHtml::link(CHtml::image(Base . '/images/logo_CSC_quadri.jpg', 'CSC', array('class' => 'logo')), 'http://www.csc.asso.fr/'); ?>
                            <?php echo CHtml::link(CHtml::image(Base . '/images/logobb.png', 'Biobanques', array('class' => 'logo')), 'http://www.biobanques.eu/'); ?>
                            <?php echo CHtml::link(CHtml::image(Base . '/images/logo_inserm.jpg', 'Inserm', array('class' => 'logo')), 'https://www.inserm.fr/'); ?>

                        </div>
                        Copyright &copy; <?php echo date('Y'); ?> by Biobanques. Version 0.5.<br/>
                        All Rights Reserved.
                    </div>
                </div><!-- footer -->
            </nav>
        </div><!-- page -->
        <!-- Bootstrap core JavaScript
================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/datePicker.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/singleDatePicker.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/maintenance.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
        <script src="js/jquery.uix.multiselect.js"></script>
        <script src="js/locales/jquery.uix.multiselect_fr.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/multiselect.js"></script>
    </body>
</html>