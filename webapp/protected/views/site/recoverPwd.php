<?php
/* @var $this SiteController */
?>
<h1><?php echo Yii::t('common', 'forgotedPwd'); ?></h1>

<hr />

<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'recover-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <div class="row">
        <div class="col-lg-4">
            <?php echo $form->labelEx($model, 'login'); ?>
            <?php echo $form->textField($model, 'login'); ?>
            <?php echo $form->error($model, 'login'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <?php echo $form->labelEx($model, 'email'); ?>
            <?php echo $form->textField($model, 'email'); ?>
            <?php echo $form->error($model, 'email'); ?>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="row buttons">
            <?php echo CHtml::submitButton(Yii::t('button', 'submit'), array('class' => 'btn btn-primary')); ?>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>