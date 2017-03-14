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
    <?php echo CHtml::submitButton(Yii::t('common', 'clinicien'), array('name' => 'clinicien', 'class' => 'btn btn-primary')); ?>
</a>
<a href="#" class="btn btn-sq-lg btn-default userProfil">
    <i class="fa fa-user fa-5x"></i><br/>
    <?php echo Yii::t('common', 'subscribeAs'); ?> <br>
    <?php echo CHtml::submitButton(Yii::t('common', 'neuropathologiste'), array('name' => 'neuropathologiste', 'class' => 'btn btn-primary')); ?>
</a>
<a href="#" class="btn btn-sq-lg btn-default userProfil">
    <i class="fa fa-user fa-5x"></i><br/>
    <?php echo Yii::t('common', 'subscribeAs'); ?> <br>
    <?php echo CHtml::submitButton(Yii::t('common', 'geneticien'), array('name' => 'geneticien', 'class' => 'btn btn-primary')); ?>
</a>
<a href="#" class="btn btn-sq-lg btn-default userProfil">
    <i class="fa fa-user fa-5x"></i><br/>
    <?php echo Yii::t('common', 'subscribeAs'); ?> <br>
    <?php echo CHtml::submitButton(Yii::t('common', 'chercheur'), array('name' => 'chercheur', 'class' => 'btn btn-primary')); ?>
</a>

<a href="#" class="btn btn-sq-lg btn-default userProfil">
    <i class="fa fa-user fa-5x"></i><br/>
    <?php echo Yii::t('common', 'subscribeAs'); ?> <br>
    <?php echo CHtml::submitButton(Yii::t('common', 'clinicienMaster'), array('name' => 'clinicienMaster', 'class' => 'btn btn-primary')); ?>
</a>
<a href="#" class="btn btn-sq-lg btn-default userProfil">
    <i class="fa fa-user fa-5x"></i><br/>
    <?php echo Yii::t('common', 'subscribeAs'); ?> <br>
    <?php echo CHtml::submitButton(Yii::t('common', 'neuroMaster'), array('name' => 'neuroMaster', 'class' => 'btn btn-primary')); ?>
</a>
<a href="#" class="btn btn-sq-lg btn-default userProfil">
    <i class="fa fa-user fa-5x"></i><br/>
    <?php echo Yii::t('common', 'subscribeAs'); ?> <br>
    <?php echo CHtml::submitButton(Yii::t('common', 'geneticienMaster'), array('name' => 'geneticienMaster', 'class' => 'btn btn-primary')); ?>
</a>
<?php $this->endWidget(); ?>