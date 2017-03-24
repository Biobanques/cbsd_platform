<?php
/* @var $this SiteController */
/* @var $model User */
?>

<h1><?php echo Yii::t('button', 'subscribe'); ?></h1>

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
    <?php echo CHtml::submitButton(Yii::t('profile', 'clinicien'), array('name' => 'clinicien', 'class' => 'btn btn-primary')); ?>
</a>
<a href="#" class="btn btn-sq-lg btn-default userProfil">
    <i class="fa fa-user fa-5x"></i><br/>
    <?php echo Yii::t('common', 'subscribeAs'); ?> <br>
    <?php echo CHtml::submitButton(Yii::t('profile', 'neuropathologiste'), array('name' => 'neuropathologiste', 'class' => 'btn btn-primary')); ?>
</a>
<a href="#" class="btn btn-sq-lg btn-default userProfil">
    <i class="fa fa-user fa-5x"></i><br/>
    <?php echo Yii::t('common', 'subscribeAs'); ?> <br>
    <?php echo CHtml::submitButton(Yii::t('profile', 'geneticien'), array('name' => 'geneticien', 'class' => 'btn btn-primary')); ?>
</a>
<a href="#" class="btn btn-sq-lg btn-default userProfil">
    <i class="fa fa-user fa-5x"></i><br/>
    <?php echo Yii::t('common', 'subscribeAs'); ?> <br>
    <?php echo CHtml::submitButton(Yii::t('profile', 'chercheur'), array('name' => 'chercheur', 'class' => 'btn btn-primary')); ?>
</a>

<a href="#" class="btn btn-sq-lg btn-default userProfil">
    <i class="fa fa-user fa-5x"></i><br/>
    <?php echo Yii::t('common', 'subscribeAs'); ?> <br>
    <?php echo CHtml::submitButton(Yii::t('profile', 'clinicienMaster'), array('name' => 'clinicienMaster', 'class' => 'btn btn-primary')); ?>
</a>
<a href="#" class="btn btn-sq-lg btn-default userProfil">
    <i class="fa fa-user fa-5x"></i><br/>
    <?php echo Yii::t('common', 'subscribeAs'); ?> <br>
    <?php echo CHtml::submitButton(Yii::t('profile', 'neuroMaster'), array('name' => 'neuroMaster', 'class' => 'btn btn-primary')); ?>
</a>
<a href="#" class="btn btn-sq-lg btn-default userProfil">
    <i class="fa fa-user fa-5x"></i><br/>
    <?php echo Yii::t('common', 'subscribeAs'); ?> <br>
    <?php echo CHtml::submitButton(Yii::t('profile', 'geneticienMaster'), array('name' => 'geneticienMaster', 'class' => 'btn btn-primary')); ?>
</a>
<?php $this->endWidget(); ?>