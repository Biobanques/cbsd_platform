
<div >
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'question-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="note"><?php echo Yii::t('common', 'ChampsObligatoires'); ?></p>

    <?php echo $form->errorSummary($model, null, null, array('class' => 'alert alert-error')); ?>
    <p>*L'id doit être unique parmi les questions. Il permet de gérer des mécanismes liées à l'ordonnancement des questions et aux traitements de validation.</p>
    <p>*Les valeurs sont utilisées pour les listes (radio bouton et listes déroulantes). Le séparateur de valeurs est la virgule.</p>

    <div >
        <div class="col-lg-6">
            <?php echo $form->labelEx($model, 'id'); ?>
            <?php echo $form->textField($model, 'id', array('size' => 5, 'maxlength' => 45)); ?>
            <?php echo $form->error($model, 'id'); ?>
        </div>
        <div class="col-lg-6">
            <?php echo $form->labelEx($model, 'label'); ?>
            <?php echo $form->textField($model, 'label', array('size' => 5)); ?>
            <?php echo $form->error($model, 'label'); ?>

        </div>
    </div>
    <div>
        <div class="col-lg-12">
            <?php echo $form->labelEx($model, 'type'); ?>
            <?php echo $form->dropDownList($model, 'type', $model->getArrayTypes(), array('prompt' => '----')); ?>
            <?php echo $form->error($model, 'type'); ?>
        </div>
    </div>
    <div>
        <div class="col-lg-6">
            <?php echo $form->labelEx($model, 'style'); ?>
            <?php echo $form->dropDownList($model, 'style', $model->getArrayStyles()); ?>
            <?php echo $form->error($model, 'style'); ?>
        </div>
        <div class="col-lg-6">
            <?php echo $form->labelEx($model, 'values'); ?>
            <?php echo $form->textField($model, 'values', array('size' => 5)); ?>
            <?php echo $form->error($model, 'values'); ?>
        </div>
    </div>
    <div>
        <div class="col-lg-6">
            <?php echo $form->labelEx($model, 'precomment'); ?>
            <?php echo $form->textField($model, 'precomment'); ?>
            <?php echo $form->error($model, 'precomment'); ?>
        </div>
        <div class="col-lg-6">
            <?php echo $form->labelEx($model, 'help'); ?>
            <?php echo $form->textField($model, 'help'); ?>
            <?php echo $form->error($model, 'help'); ?>
        </div>
    </div>
    <div class="buttons">
        <?php echo CHtml::submitButton('Enregistrer', array('class' => 'btn btn-default', 'style' => 'margin-top: 8px;')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->


