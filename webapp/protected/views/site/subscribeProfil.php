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
    <?php echo CHtml::submitButton(Yii::t('profile', 'Clinicien'), array('name' => 'Clinicien', 'class' => 'btn btn-primary')); ?>
</a>
<a href="#" class="btn btn-sq-lg btn-default userProfil">
    <i class="fa fa-user fa-5x"></i><br/>
    <?php echo Yii::t('common', 'subscribeAs'); ?> <br>
    <?php echo CHtml::submitButton(Yii::t('profile', 'Neuropathologiste'), array('name' => 'Neuropathologiste', 'class' => 'btn btn-primary')); ?>
</a>
<a href="#" class="btn btn-sq-lg btn-default userProfil">
    <i class="fa fa-user fa-5x"></i><br/>
    <?php echo Yii::t('common', 'subscribeAs'); ?> <br>
    <?php echo CHtml::submitButton(Yii::t('profile', 'Généticien'), array('name' => 'Généticien', 'class' => 'btn btn-primary')); ?>
</a>
<a href="#" class="btn btn-sq-lg btn-default userProfil">
    <i class="fa fa-user fa-5x"></i><br/>
    <?php echo Yii::t('common', 'subscribeAs'); ?> <br>
    <?php echo CHtml::submitButton(Yii::t('profile', 'Chercheur'), array('name' => 'Chercheur', 'class' => 'btn btn-primary')); ?>
</a>

<a href="#" class="btn btn-sq-lg btn-default userProfil">
    <i class="fa fa-user fa-5x"></i><br/>
    <?php echo Yii::t('common', 'subscribeAs'); ?> <br>
    <?php echo CHtml::submitButton(Yii::t('profile', 'Clinicien Master'), array('name' => 'Clinicien Master', 'class' => 'btn btn-primary')); ?>
</a>
<a href="#" class="btn btn-sq-lg btn-default userProfil">
    <i class="fa fa-user fa-5x"></i><br/>
    <?php echo Yii::t('common', 'subscribeAs'); ?> <br>
    <?php echo CHtml::submitButton(Yii::t('profile', 'Neuropathologiste Master'), array('name' => 'Neuropathologiste Master', 'class' => 'btn btn-primary')); ?>
</a>
<a href="#" class="btn btn-sq-lg btn-default userProfil">
    <i class="fa fa-user fa-5x"></i><br/>
    <?php echo Yii::t('common', 'subscribeAs'); ?> <br>
    <?php echo CHtml::submitButton(Yii::t('profile', 'Généticien Master'), array('name' => 'Généticien Master', 'class' => 'btn btn-primary')); ?>
</a>
<?php $this->endWidget(); ?>