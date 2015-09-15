<?php
/* @var $this PatientController */
/* @var $model Patient */
/* @var $form CActiveForm */
?>

<h1> Recherche de patient </h1>

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
    
    <p>Bienvenue <?php echo Yii::app()->user->name ?></p>
    <p>Veuillez renseigner les informations identifiantes patient pour accéder à son dossier.</p>
<hr />
    <p class="note">Les champs avec <span class="required">*</span> sont requis.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row" style="float:left; margin-left:5px">
        <?php echo $form->labelEx($model, 'prenom'); ?>
        <?php echo $form->textField($model, 'prenom'); ?>
        <?php echo $form->error($model, 'prenom'); ?>
    </div>
    
    <div class="row" style="float:left; margin-left:5px">
        <?php echo $form->labelEx($model, 'nom'); ?>
        <?php echo $form->textField($model, 'nom'); ?>
        <?php echo $form->error($model, 'nom'); ?>
    </div>

    <div class="row" style="float:left; margin-left:5px">
        <?php echo $form->labelEx($model, 'date_naissance'); ?>
        <?php echo $form->textField($model, 'date_naissance'); ?>
        <?php echo $form->error($model, 'date_naissance'); ?>
    </div>
    
    <div style="clear:both;"></div>

    <div class="row buttons" style="margin-left:50%;">
        <?php echo CHtml::submitButton('valider'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->