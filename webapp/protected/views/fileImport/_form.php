<div class="form" style="margin-left:30px;">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="note"><?php echo Yii::t("common", "requiredField"); ?></p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-lg-12">
            <?php echo $form->labelEx($model, 'currentColumn'); ?>
            <?php echo $form->textField($model, 'currentColumn', array('size' => 20, 'maxlength' => 250)); ?>
            <?php echo $form->error($model, 'currentColumn'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?php echo $form->labelEx($model, 'newColumn'); ?>
            <?php echo $form->textField($model, 'newColumn', array('size' => 20, 'maxlength' => 250)); ?>
            <?php echo $form->error($model, 'newColumn'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?php echo $form->labelEx($model, 'type'); ?>
            <?php echo $form->dropdownlist($model, 'type', $model->getTypesQuestions(), array('prompt' => '----')); ?>
            <?php echo $form->error($model, 'type'); ?>
        </div>
    </div>

    <div class="row buttons">
        <div class="col-lg-12">
            <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('button', 'createBtn') : Yii::t('button', 'updateBtn'), array('class' => 'btn btn-primary')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->