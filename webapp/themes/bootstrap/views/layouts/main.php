<?php
/* @var $this Controller */
define('Base', Yii::app()->request->baseUrl);
define('BaseTheme', Yii::app()->theme->baseUrl);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/styles.css" />

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/questionnaire.css" />

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />


        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
            <title><?php echo CHtml::encode($this->pageTitle); ?></title>

            <?php Yii::app()->bootstrap->register(); ?>
    </head>

    <?php
    $menuItems = array(
        array('label' => Yii::t('common', 'accueil'), 'url' => array('/site/index'), 'visible' => !Yii::app()->user->isGuest),
        array('label' => 'Saisir une fiche patient', 'url' => array('/site/patient'), 'visible' => !Yii::app()->user->isGuest),
        array('label' => 'Administration', 'url' => array('/administration/index'), 'visible' => Yii::app()->user->isAdmin()),
        array('label' => Yii::t('common', 'seconnecter'), 'url' => array('/site/login'), 'visible' => Yii::app()->user->isGuest),
        array('label' => Yii::t('common', 'sedeconnecter') . ' (' . Yii::app()->user->name . ')', 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest),
        array('label' => 'Accédez en tant que : ', 'url' => '', 'visible' => !Yii::app()->user->isGuest));
    if (!Yii::app()->user->isGuest)
        $menuItems[] = array(
            'template' => GetProfil::getHTML(),
        );



    $this->widget('bootstrap.widgets.TbNavbar', array(
        'brandUrl' => array('/site/index'),
        'items' => array(
            array(
                'class' => 'bootstrap.widgets.TbMenu',
                'items' => $menuItems
            )
        )
    ));
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
                        Copyright &copy; <?php echo date('Y'); ?> by Biobanques.<br/>
                        All Rights Reserved.
                    </div>
                </div><!-- footer -->
            </nav>
        </div><!-- page -->
        <?php
        if (isset($_POST['profil'])) {
            $_SESSION['profile'] = $_POST['profil'];
        }
        ?>
    </body>
</html>
