<?php
Yii::app()->clientScript->registerScript('typeQuestion', "
$('#QuestionForm_type').change(function(){
    var e = document.getElementById('QuestionForm_type').value;
    if (e == 'radio' || e == 'list' || e == 'checkbox') {
        $('#valueTypeQuestion').show();
    } else {
        $('#valueTypeQuestion').hide();
    }
});
");
?>

<div>
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'question-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="note"><?php echo Yii::t('common', 'requiredField'); ?></p>

    <?php echo $form->errorSummary($model, null, null, array('class' => 'alert alert-error')); ?>
    <p><b>*L'id doit être unique parmi les questions. Il permet de gérer des mécanismes liées à l'ordonnancement des questions et aux traitements de validation.</b></p>
    <p><b>*Les valeurs sont utilisées pour les listes (radio bouton et listes déroulantes). Le séparateur de valeurs est la virgule.</b></p>

    <div >
        <div class="col-lg-6">
            <?php echo $form->labelEx($model, 'precomment'); ?>
            <?php echo $form->textField($model, 'precomment'); ?>
            <?php echo $form->error($model, 'precomment'); ?>
        </div>
        <div class="col-lg-6">
            <?php echo $form->labelEx($model, 'id'); ?>
            <?php echo $form->textField($model, 'id', array('size' => 5, 'maxlength' => 45)); ?>
            <?php echo $form->error($model, 'id'); ?>
        </div>
    </div>
    <div>
        <div class="col-lg-6">
            <?php echo $form->labelEx($model, 'label'); ?>
            <?php echo $form->textField($model, 'label', array('size' => 5, 'maxlength' => 500)); ?>
            <?php echo $form->error($model, 'label'); ?>
        </div>
        <div class="col-lg-6">
            <?php echo $form->labelEx($model, 'type'); ?>
            <?php echo $form->dropDownList($model, 'type', $model->getArrayTypes(), array('prompt' => '----')); ?>
            <?php echo $form->error($model, 'type'); ?>
        </div>
    </div>
    <div>
        <div class="col-lg-6">
            <?php echo $form->labelEx($model, 'idQuestionGroup'); ?>
            <?php
            echo $form->dropDownList($model, 'idQuestionGroup', $model->getArrayGroups(), array('prompt' => '----', 'ajax' => array('type' => 'POST', 'url' => CController::createUrl('formulaire/dynamicquestions&id=' . $model->questionnaire->_id), 'update' => '#' . CHtml::activeId($model, 'idQuestionBefore'))));
            ?>
            <?php echo $form->error($model, 'idQuestionGroup'); ?>
        </div>
        <div class="col-lg-6">
            <?php echo $form->labelEx($model, 'idQuestionBefore'); ?>
            <?php echo $form->dropDownList($model, 'idQuestionBefore', array()); ?>
            <?php echo $form->error($model, 'idQuestionBefore'); ?>
        </div>
    </div>
    <div>
        <div class="col-lg-6">
            <?php echo $form->labelEx($model, 'style'); ?>
            <?php echo $form->dropDownList($model, 'style', $model->getArrayStyles()); ?>
            <?php echo $form->error($model, 'style'); ?>
        </div>
        <div class="col-lg-6" id ="valueTypeQuestion" style="display:none;">
            <?php echo $form->labelEx($model, 'values'); ?>
            <?php echo $form->textField($model, 'values', array('size' => 5)); ?>
            <?php echo $form->error($model, 'values'); ?>
        </div>
    </div>

    <div>
        <div class="col-lg-6">
            <?php echo $form->labelEx($model, 'help'); ?>
            <?php echo $form->textField($model, 'help'); ?>
            <?php echo $form->error($model, 'help'); ?>
        </div>
    </div>

    <div>
        <div class="buttons">
            <div class="col-lg-6">
                <?php echo CHtml::submitButton('Enregistrer', array('class' => 'btn btn-default', 'style' => 'margin-top: 8px;')); ?>
            </div>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->