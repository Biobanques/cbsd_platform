<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'questionnaire-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="note"><?php echo Yii::t('common', 'requiredField'); ?></p>

    <?php echo $form->errorSummary($model, null, null, array('class' => 'alert alert-error')); ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'type'); ?>
        <?php echo $form->dropDownList($model, 'type', Questionnaire::model()->getArrayTypeSorted(), array('prompt' => '---' . Yii::t('common', 'formType') . '---')); ?>
        <?php echo $form->error($model, 'type'); ?>
    </div>
    <div class="row">
        <p><?php echo Yii::t('common', 'uniqueIdForm') ?></p>
        <?php echo $form->labelEx($model, 'id'); ?>
        <?php echo $form->textField($model, 'id', array('size' => 5, 'maxlength' => 45)); ?>
        <?php echo $form->error($model, 'id'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name', array('size' => 5, 'maxlength' => 45)); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'description'); ?>
        <?php echo $form->textArea($model, 'description', array('size' => 5, 'style' => 'width: 400px; height: 80px;')); ?>
        <?php echo $form->error($model, 'description'); ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton(Yii::t('common', 'saveBtn'), array('class' => 'btn btn-default', 'style' => 'margin-top: 8px; padding-bottom: 23px;')); ?>     
        <?php echo CHtml::resetButton(Yii::t('common', 'reset'), array('class' => 'btn btn-default', 'style' => 'margin-top: 8px; padding-bottom: 23px;')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->