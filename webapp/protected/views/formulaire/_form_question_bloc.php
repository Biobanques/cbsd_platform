<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'questionnaireBloc-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="note"><?php echo Yii::t('common', 'requiredField'); ?></p>

    <?php echo $form->errorSummary($questionBloc, null, null, array('class' => 'alert alert-danger')); ?>

    <div class="row">
        <div class="col-lg-12">
            <p><?php echo Yii::t('common', 'chooseQuestionBlock') . $model->name; ?>.</p>
            <?php echo $form->labelEx($questionBloc, 'title'); ?>
            <?php echo $form->dropDownList($questionBloc, 'title', QuestionBloc::model()->getAllBlocsTitles(), array('prompt' => '----')); ?>
            <?php echo $form->error($questionBloc, 'title'); ?>
        </div>
    </div>

    <div class="row" id ="titleBloc" style="display:none;">
        <div class="col-lg-12">
            <?php echo $form->labelEx($questionBloc, 'id'); ?>
            <?php echo $form->textField($questionBloc, 'id'); ?>
            <?php echo $form->error($questionBloc, 'id'); ?>
        </div>
    </div>

    <div class="row buttons">
        <div class="col-lg-1 col-lg-offset-10">
            <?php echo CHtml::submitButton(Yii::t('button', 'saveBtn'), array('class' => 'btn btn-primary')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->