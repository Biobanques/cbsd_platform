<?php
/* @var $this PatientController */
/* @var $model Patient */
/* @var $form CActiveForm */
?>

<h1><?php echo Yii::t('common', 'createPatient'); ?></h1>

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

    <p><?php echo Yii::t('common', 'welcome') . Yii::app()->user->name ?></p>
    <p><?php echo Yii::t('common', 'searchPatientForm') ?></p>
    
    <hr />
    
    <p class="note"><?php echo Yii::t('common', 'requiredField') ?></p>

    <?php echo $form->errorSummary($model); ?>
    <?php echo $form->hiddenField($model, 'action', array('value' => $actionForm)); ?>

    <div class="row">
        <div class="col-lg-4">
            <?php echo $form->labelEx($model, 'prenom'); ?>
            <?php echo $form->textField($model, 'prenom'); ?>
            <?php echo $form->error($model, 'prenom'); ?>
        </div>
        <div class="col-lg-4">
            <?php echo $form->labelEx($model, 'nom_naissance'); ?>
            <?php echo $form->textField($model, 'nom_naissance'); ?>
            <?php echo $form->error($model, 'nom_naissance'); ?>
        </div>
        <div class="col-lg-4">
            <?php echo $form->labelEx($model, 'date_naissance'); ?>
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model' => $model,
                'attribute' => 'date_naissance',
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat'=>'mm/dd/yy',
                    'yearRange'=>'-200:+0',
                    'changeMonth' => true,
                  'changeYear' => true,
                    'minDate'=>'01/01/1900',      // minimum date
        'maxDate' => '0',
                ),
                'htmlOptions' => array(
                    'placeholder' => 'Format jj/mm/aaaa'
                ),
                'language' => 'fr',
            ));
            ?>
            <?php echo $form->error($model, 'date_naissance'); ?>
        </div>
    </div>

    <div class="row">
        <div class="row buttons">
            <div class="col-lg-1 col-lg-offset-10">
            <?php echo CHtml::submitButton('OK', array('class' => 'btn btn-primary')); ?>
        </div>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->