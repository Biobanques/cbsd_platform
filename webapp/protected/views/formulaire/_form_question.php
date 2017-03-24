<?php
Yii::app()->clientScript->registerScript('typeQuestion', "
$(document).ready(function(){
    var e = document.getElementById('QuestionForm_type').value;
    if (e == 'radio' || e == 'list' || e == 'checkbox') {
        $('#valueTypeQuestion').show();
    } else {
        $('#valueTypeQuestion').hide();
        $('#QuestionForm_values').val('');
    }
});

$('#QuestionForm_type').change(function(){
    var e = document.getElementById('QuestionForm_type').value;
    if (e == 'radio' || e == 'list' || e == 'checkbox') {
        $('#valueTypeQuestion').show();
    } else {
        $('#valueTypeQuestion').hide();
        $('#QuestionForm_values').val('');
    }
});
$('#question-form').submit(function(){
    var e = document.getElementById('QuestionForm_type').value;
    if (e == 'radio' || e == 'list' || e == 'checkbox') {
        $('#valueTypeQuestion').show();
    } else {
        $('#valueTypeQuestion').hide();
        $('#QuestionForm_values').val('');
    }
});
$('div .alert alert-error').removeClass('alert alert-error').addClass('alert alert-danger');
");
?>

<div class="form" style="margin-left:30px;">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'question-form',
        'enableAjaxValidation' => false,
    ));
    $this->widget('ext.tooltipster.tooltipster', array(
        'identifier' => '.tooltipster',
        'options' => array('position' => 'right')
            )
    );
    ?>

    <p class="note"><?php echo Yii::t('common', 'requiredField'); ?></p>

    <?php echo $form->errorSummary($model, null, null, array('class' => 'alert alert-danger')); ?>

    <p><b><?php echo Yii::t('common', 'uniqueIdQuestion') ?></b></p>
    <p><b><?php echo Yii::t('common', 'valuesQuestion') ?></b></p>

    <div style="border:1px solid black;">

        <h4 style="margin-left:10px;"><u><b><?php echo Yii::t('common', 'addTitleQuestion') ?></b></u>&nbsp;(<?php echo Yii::t('common', 'optional') ?>)</h4>

        <div class="row">
            <div class="col-lg-12">
                <?php echo $form->labelEx($model, 'precomment'); ?>
                <?php echo $form->textField($model, 'precomment', array("class" => "tooltipster", "title" => Yii::t('common', 'titleQuestion'))); ?>
                <?php echo $form->error($model, 'precomment'); ?>
            </div>        
        </div>

    </div>

    <hr/>

    <div style="border:1px solid black;">

        <h4 style="margin-left:10px;"><u><b><?php echo Yii::t('common', 'newQuestion') ?></b></u></h4>

        <div class="row">
            <div class="col-lg-6">
                <?php echo $form->labelEx($model, 'id'); ?>
                <?php echo $form->textField($model, 'id', array('size' => 5, 'maxlength' => 45, "class" => "tooltipster", "title" => Yii::t('common', 'idQuestion'))); ?>
                <?php echo $form->error($model, 'id'); ?>            
            </div>
            <div class="col-lg-6">
                <?php echo $form->labelEx($model, 'idQuestionGroup'); ?>
                <?php echo $form->dropDownList($model, 'idQuestionGroup', $model->getArrayGroups(), array('prompt' => '----', 'ajax' => array('type' => 'POST', 'url' => CController::createUrl('formulaire/dynamicquestions&id=' . $model->questionnaire->_id), 'update' => '#' . CHtml::activeId($model, 'idQuestionBefore')), "class" => "tooltipster", "title" => Yii::t('common', 'groupQuestion'))); ?>
                <?php echo $form->error($model, 'idQuestionGroup'); ?>      
            </div> 
        </div>

        <div class="row">
            <div class="col-lg-6">
                <?php echo $form->labelEx($model, 'label'); ?>
                <?php echo $form->textField($model, 'label', array('size' => 5, 'maxlength' => 500, "class" => "tooltipster", "title" => Yii::t('common', 'labelQuestion'))); ?>
                <?php echo $form->error($model, 'label'); ?>          
            </div>
            <div class="col-lg-6">
                <?php echo $form->labelEx($model, 'idQuestionBefore'); ?>
                <?php echo $form->dropDownList($model, 'idQuestionBefore', array()); ?>
                <?php echo $form->error($model, 'idQuestionBefore'); ?>     
            </div> 
        </div>

        <div class="row">
            <div class="col-lg-6">
                <?php echo $form->labelEx($model, 'type'); ?>
                <?php echo $form->dropDownList($model, 'type', $model->getArrayTypes(), array('prompt' => '----', "class" => "tooltipster", "title" => Yii::t('common', 'typeQuestion'))); ?>
                <?php echo $form->error($model, 'type'); ?>           
            </div>
            <div class="col-lg-6" id ="valueTypeQuestion">
                <?php echo $form->labelEx($model, 'values'); ?>
                <?php echo $form->textField($model, 'values'); ?>
                <?php echo $form->error($model, 'values'); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <?php echo $form->labelEx($model, 'style'); ?>
                <?php echo $form->dropDownList($model, 'style', $model->getArrayStyles(), array("class" => "tooltipster", "title" => Yii::t('common', 'positionQuestion'))); ?>
                <?php echo $form->error($model, 'style'); ?>    
            </div>
            <div class="col-lg-6">
                <?php echo $form->labelEx($model, 'help'); ?>
                <?php echo $form->textField($model, 'help', array("class" => "tooltipster", "title" => Yii::t('common', 'helpQuestion'))); ?>
                <?php echo $form->error($model, 'help'); ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-1 col-lg-offset-10">
            <div class="buttons">
                <?php echo CHtml::submitButton(Yii::t('button', 'saveBtn'), array('class' => 'btn btn-primary')); ?>
            </div>        
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->