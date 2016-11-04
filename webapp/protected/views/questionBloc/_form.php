<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'question-bloc-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="note"><?php echo Yii::t("common", "requiredField"); ?></p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'title'); ?>
        <?php echo $form->textField($model, 'title'); ?>
        <?php echo $form->error($model, 'title'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton(Yii::t('common', 'createBtn'), array('class' => 'btn btn-default', 'style' => 'margin-top: 8px; padding-bottom: 23px;')); ?>
        <?php echo CHtml::resetButton(Yii::t('common', 'reset'), array('class' => 'btn btn-default', 'style' => 'margin-top: 8px; padding-bottom: 23px;')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->