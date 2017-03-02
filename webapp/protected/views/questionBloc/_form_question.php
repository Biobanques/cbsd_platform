
<div class="form" style="margin-left:30px;">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'question-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="note"><?php echo Yii::t('common', 'requiredField'); ?></p>

    <?php echo $form->errorSummary($model, null, null, array('class' => 'alert alert-error')); ?>
    <p><b><?php echo Yii::t('common', 'uniqueIdQuestion') ?></b></p>
    <p><b><?php echo Yii::t('common', 'valuesQuestion') ?></b></p>

    <div style="border:1px solid black;">

        <h4 style="margin-left:10px;"><u><b><?php echo Yii::t('common', 'newQuestion') ?></b></u></h4>

        <div class="row">
            <div class="col-lg-6">
                <?php echo $form->labelEx($model, 'id'); ?>
                <?php echo $form->textField($model, 'id'); ?>
                <?php echo $form->error($model, 'id'); ?>
            </div>
            <div class="col-lg-6">
                <?php echo $form->labelEx($model, 'label'); ?>
                <?php echo $form->textField($model, 'label'); ?>
                <?php echo $form->error($model, 'label'); ?>

            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <?php echo $form->labelEx($model, 'type'); ?>
                <?php echo $form->dropDownList($model, 'type', $model->getArrayTypes(), array('prompt' => '----')); ?>
                <?php echo $form->error($model, 'type'); ?>
            </div>
            <div class="col-lg-6">
                <?php echo $form->labelEx($model, 'style'); ?>
                <?php echo $form->dropDownList($model, 'style', $model->getArrayStyles()); ?>
                <?php echo $form->error($model, 'style'); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <?php echo $form->labelEx($model, 'values'); ?>
                <?php echo $form->textField($model, 'values'); ?>
                <?php echo $form->error($model, 'values'); ?>
            </div>
            <div class="col-lg-6">
                <?php echo $form->labelEx($model, 'precomment'); ?>
                <?php echo $form->textField($model, 'precomment'); ?>
                <?php echo $form->error($model, 'precomment'); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <?php echo $form->labelEx($model, 'help'); ?>
                <?php echo $form->textField($model, 'help'); ?>
                <?php echo $form->error($model, 'help'); ?>
            </div>
        </div>

        <div class="row buttons">
            <div class="col-lg-1 col-lg-offset-10">
                <?php echo CHtml::submitButton(Yii::t('common', 'saveBtn'), array('class' => 'btn btn-primary')); ?>
            </div>
        </div>
    </div>
    <?php $this->endWidget(); ?>

</div><!-- form -->


