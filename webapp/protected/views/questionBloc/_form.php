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
        <div class="col-lg-12">
            <?php echo $form->labelEx($model, 'title'); ?>
            <?php echo $form->textField($model, 'title'); ?>
            <?php echo $form->error($model, 'title'); ?>
        </div>
    </div>

    <div class="row buttons">
        <div class="col-lg-12">
            <?php echo CHtml::submitButton(Yii::t('common', 'createBtn'), array('class' => 'btn btn-primary', 'style' => 'padding-bottom: 23px;')); ?>
            <?php echo CHtml::resetButton(Yii::t('common', 'reset'), array('class' => 'btn btn-danger', 'style' => 'padding-bottom: 23px;')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->