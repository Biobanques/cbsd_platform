<?php
/* @var $this SiteController */
/* @var $model User */
?>

<h1><?php echo Yii::t('common', 'subscribe'); ?></h1>

<?php
$this->beginWidget('CActiveForm', array(
    'id' => 'subscribe-form',
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
));
?>
<a href="#" class="btn btn-sq-lg btn-default userProfil">
    <i class="fa fa-user fa-5x"></i><br/>
    <?php echo Yii::t('common', 'subscribeAs'); ?> <br>
    <?php echo CHtml::submitButton('clinicien', array('name' => 'clinicien')); ?>
</a>
<a href="#" class="btn btn-sq-lg btn-default userProfil">
    <i class="fa fa-user fa-5x"></i><br/>
    <?php echo Yii::t('common', 'subscribeAs'); ?> <br>
    <?php echo CHtml::submitButton('neuropathologiste', array('name' => 'neuropathologiste')); ?>
</a>
<a href="#" class="btn btn-sq-lg btn-default userProfil">
    <i class="fa fa-user fa-5x"></i><br/>
    <?php echo Yii::t('common', 'subscribeAs'); ?> <br>
    <?php echo CHtml::submitButton('geneticien', array('name' => 'geneticien')); ?>
</a>
<a href="#" class="btn btn-sq-lg btn-default userProfil">
    <i class="fa fa-user fa-5x"></i><br/>
    <?php echo Yii::t('common', 'subscribeAs'); ?> <br>
    <?php echo CHtml::submitButton('chercheur', array('name' => 'chercheur')); ?>
</a>
<?php $this->endWidget(); ?>