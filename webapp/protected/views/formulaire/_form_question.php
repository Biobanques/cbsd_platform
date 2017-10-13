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
    $('#QuestionForm_idQuestionGroup option:first-child').attr('selected', true);
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

    <?php echo $form->errorSummary($questionForm, null, null, array('class' => 'alert alert-danger')); ?>

    <p><b><?php echo Yii::t('common', 'uniqueIdQuestion') ?></b></p>
    <p><b><?php echo Yii::t('common', 'valuesQuestion') ?></b></p>

    <div style="border:1px solid black;">

        <h4 style="margin-left:10px;"><u><b><?php echo Yii::t('common', 'addTitleQuestion') ?></b></u>&nbsp;(<?php echo Yii::t('common', 'optional') ?>)</h4>

        <div class="row">
            <div class="col-lg-12">
                <?php echo $form->labelEx($questionForm, 'precomment'); ?>
                <?php echo $form->textField($questionForm, 'precomment', array("class" => "tooltipster", "title" => Yii::t('common', 'titleQuestion'))); ?>
                <?php echo $form->error($questionForm, 'precomment'); ?>
            </div>        
        </div>

    </div>

    <hr/>

    <div style="border:1px solid black;">

        <h4 style="margin-left:10px;"><u><b><?php echo Yii::t('common', 'newQuestion') ?></b></u></h4>

        <div class="row">
            <div class="col-lg-6">
                <?php echo $form->labelEx($questionForm, 'id'); ?>
                <?php echo $form->textField($questionForm, 'id', array('size' => 5, 'maxlength' => 45, "class" => "tooltipster", "title" => Yii::t('common', 'idQuestion'))); ?>
                <?php echo $form->error($questionForm, 'id'); ?>            
            </div>
            <div class="col-lg-6">
                <?php echo $form->labelEx($questionForm, 'idQuestionGroup'); ?>
                <?php echo $form->dropDownList($questionForm, 'idQuestionGroup', $questionForm->getArrayGroups(), array('prompt' => '----', 'ajax' => array('type' => 'POST', 'url' => CController::createUrl('formulaire/dynamicquestions&id=' . $questionForm->questionnaire->_id), 'update' => '#' . CHtml::activeId($questionForm, 'idQuestionBefore')), "class" => "tooltipster", "title" => Yii::t('common', 'groupQuestion'))); ?>
                <?php echo $form->error($questionForm, 'idQuestionGroup'); ?>      
            </div> 
        </div>

        <div class="row">
            <div class="col-lg-6">
                <?php echo $form->labelEx($questionForm, 'label'); ?>
                <?php echo $form->textField($questionForm, 'label', array('size' => 5, 'maxlength' => 500, "class" => "tooltipster", "title" => Yii::t('common', 'labelQuestion'))); ?>
                <?php echo $form->error($questionForm, 'label'); ?>          
            </div>
            <div class="col-lg-6">
                <?php echo $form->labelEx($questionForm, 'idQuestionBefore'); ?>
                <?php echo $form->dropDownList($questionForm, 'idQuestionBefore', array()); ?>
                <?php echo $form->error($questionForm, 'idQuestionBefore'); ?>     
            </div> 
        </div>

        <div class="row">
            <div class="col-lg-6">
                <?php echo $form->labelEx($questionForm, 'type'); ?>
                <?php echo $form->dropDownList($questionForm, 'type', $questionForm->getArrayTypes(), array('prompt' => '----', "class" => "tooltipster", "title" => Yii::t('common', 'typeQuestion'))); ?>
                <?php echo $form->error($questionForm, 'type'); ?>           
            </div>
            <div class="col-lg-6" id ="valueTypeQuestion">
                <?php echo $form->labelEx($questionForm, 'values'); ?>
                <?php echo $form->textField($questionForm, 'values'); ?>
                <?php echo $form->error($questionForm, 'values'); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <?php echo $form->labelEx($questionForm, 'style'); ?>
                <?php echo $form->dropDownList($questionForm, 'style', $questionForm->getArrayStyles(), array("class" => "tooltipster", "title" => Yii::t('common', 'positionQuestion'))); ?>
                <?php echo $form->error($questionForm, 'style'); ?>    
            </div>
            <div class="col-lg-6">
                <?php echo $form->labelEx($questionForm, 'help'); ?>
                <?php echo $form->textField($questionForm, 'help', array("class" => "tooltipster", "title" => Yii::t('common', 'helpQuestion'))); ?>
                <?php echo $form->error($questionForm, 'help'); ?>
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