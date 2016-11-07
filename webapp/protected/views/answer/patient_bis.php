<?php
/* @var $this PatientController */
/* @var $model Patient */
/* @var $form CActiveForm */
?>

<h1> <?php echo $actionForm == 'create' ? Yii::t('common', 'creation') : Yii::t('common', 'advancedsearch'); ?></h1>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'patient-patient-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // See class documentation of CActiveForm for details on this,
        // you need to use the performAjaxValidation()-method described there.
        //'action' => Yii::app()->createUrl('answer/createPatient'), // à modifier
        'enableAjaxValidation' => false,
    ));
    ?>
    <hr />
    <p class="note"><?php echo Yii::t('common', 'requiredField'); ?></p>

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
        <div class="col-lg-4">
            <?php echo $form->labelEx($model, 'nom'); ?>
            <?php echo $form->textField($model, 'nom'); ?>
            <?php echo $form->error($model, 'nom'); ?>
        </div>

        <div class="col-lg-4">
            <?php echo $form->labelEx($model, 'sexe'); ?>
            <?php echo $form->dropDownList($model, 'sexe', $model->getGenre(), array('prompt' => '----')); ?>
            <?php echo $form->error($model, 'sexe'); ?>
        </div>

        <div class="col-lg-4">
            <?php echo $form->labelEx($model, 'source'); ?>
            <?php echo $form->dropDownList($model, 'source', $model->getSource(), array('prompt' => '----')); ?>
            <?php echo $form->error($model, 'source'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 row buttons">
            <?php echo CHtml::submitButton($actionForm == 'create' ? Yii::t('common', 'createBtn') : Yii::t('common', 'search')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->