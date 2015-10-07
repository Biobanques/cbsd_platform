<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'questionnaireBloc-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="note"><?php echo Yii::t('common', 'ChampsObligatoires'); ?></p>

    <?php echo $form->errorSummary($questionBloc, null, null, array('class' => 'alert alert-error')); ?>
    <div class="row">
        <?php echo $form->labelEx($questionBloc, 'id'); ?>
        <?php echo $form->textField($questionBloc, 'id'); ?>
        <?php echo $form->error($questionBloc, 'id'); ?>
    </div>
    <div class="row">
        <p>Choisissez un bloc de questions à inclure dans le formulaire <?php echo $model->name; ?>.</p>
        <?php echo $form->labelEx($questionBloc, 'title'); ?>
        <?php echo $form->dropDownList($questionBloc, 'title', QuestionBloc::model()->getBlocTitle(), array('prompt' => '----')); ?>
        <?php echo $form->error($questionBloc, 'title'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($questionBloc, 'parent_group'); ?>
        <?php echo $form->dropDownList($questionBloc, 'parent_group', $questionGroup->getOnglets(), array('prompt' => '---')); ?>
        <?php echo $form->error($questionBloc, 'parent_group'); ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton('Enregistrer'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
