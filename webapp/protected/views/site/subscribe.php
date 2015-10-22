<?php
/* @var $this UserController */
/* @var $model User */
if (isset($_POST['clinicien']))
    $profil = "clinicien";
if (isset($_POST['neuropathologiste']))
    $profil = "neuropathologiste";
if (isset($_POST['geneticien']))
    $profil = "geneticien";
if (isset($_POST['chercheur']))
    $profil = "chercheur";
?>

<h1><?php echo Yii::t('common','subscribe');?></h1>


<?php 
if (isset(Yii::app()->user->id))
    echo $this->renderPartial('_updateSubscribeForm', array('model'=>$model, 'profil' => $profil));
else
    echo $this->renderPartial('_subscribeForm', array('model'=>$model, 'profil' => $profil));
?>