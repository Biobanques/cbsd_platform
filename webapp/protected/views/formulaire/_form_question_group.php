<?php
Yii::app()->clientScript->registerScript('idTitle', "
$('#QuestionGroup_title').change(function(){
    var e = document.getElementById('QuestionGroup_title').value;
    if (e !== '') {
        $('#titleGroup').show();
    } else {
        $('#titleGroup').hide();
    }
});
");
?>

<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'questionnaireGroup-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="note"><?php echo Yii::t('common', 'requiredField'); ?></p>

    <?php echo $form->errorSummary($model, null, null, array('class' => 'alert alert-error')); ?>
    <div class="row">
        <p>Le titre est le libellé affiché de l'onglet.</p>
        <?php echo $form->labelEx($model, 'title'); ?>
        <?php echo $form->textField($model, 'title'); ?>
        <?php echo $form->error($model, 'title'); ?>
    </div>
    <div class="row" id ="titleGroup" style="display:none;">
        <p>L'id permet de repérer rapidement l'onglet dans l arborescence de questions.</p>
        <?php echo $form->labelEx($model, 'id'); ?>
        <?php echo $form->textField($model, 'id'); ?>
        <?php echo $form->error($model, 'id'); ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton('Enregistrer', array('class' => 'btn btn-default', 'style' => 'margin-top: 8px; padding-bottom: 23px;')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->