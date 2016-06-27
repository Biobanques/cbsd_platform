<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'questionnaire-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="note"><?php echo Yii::t('common', 'ChampsObligatoires'); ?></p>

    <?php echo $form->errorSummary($model, null, null, array('class' => 'alert alert-error')); ?>
    <div class="row">
        <p>Choisissez le type de formulaire. </p>
        <?php echo $form->labelEx($model, 'type'); ?>
        <?php echo $form->dropDownList($model, 'type', Questionnaire::model()->getArrayTypeSorted(), array('prompt' => '--- Type de formulaire ---')); ?>
        <?php echo $form->error($model, 'type'); ?>
    </div>
    <div class="row">
        <p>Id doit être un indentifiant unique qui vous permettra de reconnaitre votre formulaire rapidement. </p>
        <?php echo $form->labelEx($model, 'id'); ?>
        <?php echo $form->textField($model, 'id', array('size' => 5, 'maxlength' => 45)); ?>
        <?php echo $form->error($model, 'id'); ?>
    </div>
    <div class="row">
        <p>Le nom du formulaire est le nom qui apparaitra en titre du formulaire.<br> Il est préférable de le penser court et explicite.</p>
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name', array('size' => 5, 'maxlength' => 45)); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'description'); ?>
        <?php echo $form->textArea($model, 'description', array('size' => 5, 'style' => 'width: 400px; height: 80px;')); ?>
        <?php echo $form->error($model, 'description'); ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton('Enregistrer', array('class' => 'btn btn-default', 'style' => 'margin-top: 8px; padding-bottom: 23px;')); ?>     
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->