<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/styles.css" />

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/questionnaire.css" />

        
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>

        <?php Yii::app()->bootstrap->register(); ?>
    </head>

    <?php
    $this->widget('bootstrap.widgets.TbNavbar', array(
        'brandUrl'=> array('/site/index'),
        'items' => array(
            array(
                'class' => 'bootstrap.widgets.TbMenu',
                'items' => array(
                    array('label' => Yii::t('common', 'accueil'), 'url' => array('/site/index'), 'visible' => !Yii::app()->user->isGuest),
                    array('label' => 'Patient', 'url' => array('/site/patient'), 'visible' => !Yii::app()->user->isGuest),
                    array('label' => 'Administration', 'url' => array('/administration/index'), 'visible' => !Yii::app()->user->isGuest),
                    array('label' => Yii::t('common', 'seconnecter'), 'url' => array('/site/login'), 'visible' => Yii::app()->user->isGuest),
                    array('label' => Yii::t('common', 'sedeconnecter') . ' (' . Yii::app()->user->name . ')', 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest)
                ),
            )
        )
            )
    );
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

            <div id="footer">
                Copyright &copy; <?php echo date('Y'); ?> by Biobanques.<br/>
                All Rights Reserved.<br/>

            </div><!-- footer -->

        </div><!-- page -->

    </body>
</html>
