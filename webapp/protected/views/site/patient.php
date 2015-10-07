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
    <p>Cet outil vous permet de retrouver les fiches patient à partir de son identité.</p>
<hr />
    <p class="note">Les champs avec <span class="required">*</span> sont requis.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row" style="float:left; margin-left:0">
        <?php echo $form->labelEx($model, 'prenom'); ?>
        <?php echo $form->textField($model, 'prenom'); ?>
        <?php echo $form->error($model, 'prenom'); ?>
    </div>
    
    <div class="row" style="float:left; margin-left:35px">
        <?php echo $form->labelEx($model, 'nom_naissance'); ?>
        <?php echo $form->textField($model, 'nom_naissance'); ?>
        <?php echo $form->error($model, 'nom_naissance'); ?>
    </div>

    <div class="row" style="float:left; margin-left:35px">
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