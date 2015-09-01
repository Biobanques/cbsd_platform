<?php
/* @var $this PatientController */
/* @var $model Patient */
/* @var $form CActiveForm */
?>

<h1> Saisie patient </h1>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'patient-patient-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // See class documentation of CActiveForm for details on this,
        // you need to use the performAjaxValidation()-method described there.
        'action' => Yii::app()->createUrl('answer/affichepatient'),
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'nom'); ?>
        <?php echo $form->textField($model, 'nom'); ?>
        <?php echo $form->error($model, 'nom'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'prenom'); ?>
        <?php echo $form->textField($model, 'prenom'); ?>
        <?php echo $form->error($model, 'prenom'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'date_naissance'); ?>
        <?php echo $form->textField($model, 'date_naissance'); ?>
        <?php echo $form->error($model, 'date_naissance'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'nom_naissance'); ?>
        <?php echo $form->textField($model, 'nom_naissance'); ?>
        <?php echo $form->error($model, 'nom_naissance'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'sexe'); ?>
        <?php echo $form->dropDownList($model, 'sexe', array('M' => 'Masculin', 'F' => 'Féminin')); ?>
        <?php echo $form->error($model, 'sexe'); ?>
    </div>


    <div class="row buttons">
        <?php echo CHtml::submitButton('Submit'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->