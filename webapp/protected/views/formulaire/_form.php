<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'questionnaire-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="note"><?php echo Yii::t('common', 'requiredField'); ?></p>

    <?php echo $form->errorSummary($model, null, null, array('class' => 'alert alert-danger')); ?>
    <div class="row">
        <div class="col-lg-12">
            <?php echo $form->labelEx($model, 'type'); ?>
            <?php echo $form->dropDownList($model, 'type', Questionnaire::model()->getArrayTypeSorted(), array('prompt' => '---' . Yii::t('common', 'formType') . '---')); ?>
            <?php echo $form->error($model, 'type'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <p><?php echo Yii::t('common', 'uniqueIdForm') ?></p>
            <?php echo $form->labelEx($model, 'id'); ?>
            <?php echo $form->textField($model, 'id', array('size' => 5, 'maxlength' => 45)); ?>
            <?php echo $form->error($model, 'id'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?php echo $form->labelEx($model, 'name'); ?>
            <?php echo $form->textField($model, 'name', array('size' => 5, 'maxlength' => 45)); ?>
            <?php echo $form->error($model, 'name'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?php echo $form->labelEx($model, 'description'); ?>
            <?php echo $form->textArea($model, 'description', array('size' => 5, 'style' => 'width: 400px; height: 80px;')); ?>
            <?php echo $form->error($model, 'description'); ?>
        </div>
    </div>
    <div class="row buttons">
        <div class="col-lg-12">
            <?php echo CHtml::submitButton(Yii::t('common', 'createBtn'), array('class' => 'btn btn-primary')); ?>     
            <?php echo CHtml::resetButton(Yii::t('common', 'reset'), array('class' => 'btn btn-danger')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->