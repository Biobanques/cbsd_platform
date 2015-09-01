<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/styles.css" />
    
           <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/questionnaire.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

	<?php Yii::app()->bootstrap->register(); ?>
</head>

<body>

<?php
$this->widget('bootstrap.widgets.TbNavbar',array(
    'items'=>array(
        array(
            'class'=>'bootstrap.widgets.TbMenu',
            'items'=>array(
                array('label'=>Yii::t('common', 'accueil'), 'url'=>array('/site/index'), 'visible'=>!Yii::app()->user->isGuest),
                //array('label'=>'Questionnaires', 'url'=>array('/questionnaire/index'), 'visible'=>!Yii::app()->user->isGuest),
                //array('label'=>Yii::t('common', 'mydocuments'), 'url'=>array('/answer/index'), 'visible'=>!Yii::app()->user->isGuest),
               // array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
                array('label'=>'Patient', 'url'=>array('/site/patient'), 'visible'=>!Yii::app()->user->isGuest),
                //array('label'=>'Contact', 'url'=>array('/site/contact'), 'visible'=>!Yii::app()->user->isGuest),
                array('label'=>Yii::t('common', 'seconnecter'), 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
                array('label'=>Yii::t('common', 'sedeconnecter').' ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
            ),
        ),
    ),
)); ?>

<div class="container" id="page">

	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

       <?php $this->widget('bootstrap.widgets.TbAlert', array(
        'block'=>true, // display a larger alert block?
        'fade'=>true, // use transitions?
        'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
        'alerts'=>array( // configurations per alert type
            'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
            'warning'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
        ),
         )); ?>
                
	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by Biobanques.<br/>
		All Rights Reserved.<br/>

	</div><!-- footer -->

</div><!-- page -->

</body>
</html>