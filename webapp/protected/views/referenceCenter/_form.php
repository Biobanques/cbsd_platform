<div class="form" style="margin-left:30px;">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'referenceCenter-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="note"><?php echo Yii::t("common", "requiredField"); ?></p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-lg-12">
            <?php echo CHtml::activeLabel($model, 'center', array('required' => true)); ?>
            <?php echo $form->textField($model, 'center', array('size' => 20, 'maxlength' => 250)); ?>
            <?php echo $form->error($model, 'center'); ?>
        </div>
    </div>

    <div class="row buttons">
        <div class="col-lg-12">
            <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('button', 'createBtn') : Yii::t('button', 'updateBtn'), array('class' => 'btn btn-primary')); ?>
            <?php echo CHtml::resetButton(Yii::t('button', 'reset'), array('class' => 'btn btn-danger')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->