<div class="form" style="margin-left:30px;">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-lg-4">
            <?php echo $form->labelEx($model, 'originSamplesTissue'); ?>
            <?php echo $form->textField($model, 'originSamplesTissue', array('size' => 20, 'maxlength' => 250)); ?>
            <?php echo $form->error($model, 'originSamplesTissue'); ?>
        </div>
        <div class="col-lg-4">
            <?php echo $form->labelEx($model, 'quantityAvailable'); ?>
            <?php echo $form->textField($model, 'quantityAvailable', array('size' => 20, 'maxlength' => 250)); ?>
            <?php echo $form->error($model, 'quantityAvailable'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <?php echo $form->labelEx($model, 'storageConditions'); ?>
            <?php echo $form->textField($model, 'storageConditions', array('size' => 20, 'maxlength' => 250)); ?>
            <?php echo $form->error($model, 'storageConditions'); ?>
        </div>
    </div>

    <div class="row buttons">
        <div class="col-lg-12">
            <?php echo CHtml::submitButton(Yii::t('button', 'search'), array('class' => 'btn btn-primary')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->