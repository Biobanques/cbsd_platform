<?php
/* @var $this PatientController */
/* @var $model Patient */
/* @var $form CActiveForm */
?>

<h1> Création de patient </h1>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'patient-patient-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // See class documentation of CActiveForm for details on this,
        // you need to use the performAjaxValidation()-method described there.
        'action' => Yii::app()->createUrl('site/patientBis'), // à modifier
        'enableAjaxValidation' => false,
    ));
    ?>
    <hr />
    <p class="note">Les champs avec <span class="required">*</span> sont requis.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-lg-3">
            <?php echo $form->labelEx($model, 'prenom'); ?>
            <?php echo $form->textField($model, 'prenom'); ?>
            <?php echo $form->error($model, 'prenom'); ?>
        </div>

        <div class="col-lg-3">
            <?php echo $form->labelEx($model, 'nom_naissance'); ?>
            <?php echo $form->textField($model, 'nom_naissance'); ?>
            <?php echo $form->error($model, 'nom_naissance'); ?>
        </div>

        <div class="col-lg-3">
            <?php echo $form->labelEx($model, 'date_naissance'); ?>
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model' => $model,
                'attribute' => 'date_naissance',
                'options' => array(
                    'showAnim' => 'fold',
                ),
                'htmlOptions' => array(
                    'style' => 'height:25px;',
                    'placeholder' => 'Format jj/mm/aaaa'
                ),
                'language' => 'fr',
            ));
            ?>
            <?php echo $form->error($model, 'date_naissance'); ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-3">
            <?php echo $form->labelEx($model, 'nom'); ?>
            <?php echo $form->textField($model, 'nom'); ?>
            <?php echo $form->error($model, 'nom'); ?>
        </div>
        
        <div class="col-lg-3">
            <?php echo $form->labelEx($model, 'sexe'); ?>
            <?php echo $form->dropDownList($model, 'sexe', array("M"=>"Homme", "F"=>"Femme", "U"=>"Inconnu"), array('prompt'=>'----')); ?>
            <?php echo $form->error($model, 'sexe'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 row buttons">
            <?php echo CHtml::submitButton('Valider'); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->