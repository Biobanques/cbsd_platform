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
        <div class="col-lg-12">
            <?php echo $form->labelEx($model, 'title'); ?>
            <?php echo $form->textField($model, 'title'); ?>
            <?php echo $form->error($model, 'title'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?php echo $form->labelEx($model, 'id'); ?>
            <?php echo $form->textField($model, 'id'); ?>
            <?php echo $form->error($model, 'id'); ?>
        </div>
    </div>

    <div class="row buttons">
        <div class="col-lg-1 col-lg-offset-10">
            <?php echo CHtml::submitButton(Yii::t('common', 'saveBtn'), array('class' => 'btn btn-primary', 'style' => 'margin-top: 8px; padding-bottom: 23px;')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->