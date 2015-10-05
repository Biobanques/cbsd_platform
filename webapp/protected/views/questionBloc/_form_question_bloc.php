
<div >
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'question_bloc-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="note"><?php echo Yii::t('common', 'ChampsObligatoires'); ?></p>

    <?php echo $form->errorSummary($questionForm, null, null, array('class' => 'alert alert-error')); ?>
    <p>*L'id doit être unique parmi les questions. Il permet de gérer des mécanismes liées à l'ordonnancement des questions et aux traitements de validation.</p>
    <p>*Les valeurs sont utilisés pour les listes (radio bouton et listes déroulantes). Le séparateur de valeurs est la virgule.</p>
    <p>*Le style permet d'affecter un style css à la question. Par exemple, si vous souahitez aligner votr equestion à droite de la précédente , saisissez dans le champ le texte en gras: <b>float:right</b></p>
    <div>
        <div class="col-lg-12">
            <?php echo $form->labelEx($model, 'title'); ?>
            <?php echo $form->textField($model, 'title'); ?>
            <?php echo $form->error($model, 'title'); ?>
        </div>
    </div>
    <div >
        <div class="col-lg-6">
            <?php echo $form->labelEx($questionForm, 'id'); ?>
            <?php echo $form->textField($questionForm, 'id', array('size' => 5, 'maxlength' => 45)); ?>
            <?php echo $form->error($questionForm, 'id'); ?>
        </div>
        <div class="col-lg-6">
            <?php echo $form->labelEx($questionForm, 'label'); ?>
            <?php echo $form->textField($questionForm, 'label', array('size' => 5, 'maxlength' => 45)); ?>
            <?php echo $form->error($questionForm, 'label'); ?>

        </div>
    </div>
    <div>
        <div class="col-lg-12">
            <?php echo $form->labelEx($questionForm, 'type'); ?>
            <?php echo $form->dropDownList($questionForm, 'type', $questionForm->getArrayTypes(), array('prompt' => '----')); ?>
            <?php echo $form->error($questionForm, 'type'); ?>
        </div>
    </div>
    <div>
        <div class="col-lg-6">
            <?php echo $form->labelEx($questionForm, 'style'); ?>
            <?php echo $form->textField($questionForm, 'style', array('size' => 5, 'maxlength' => 45)); ?>
            <?php echo $form->error($questionForm, 'style'); ?>
        </div>
        <div class="col-lg-6">
            <?php echo $form->labelEx($questionForm, 'values'); ?>
            <?php echo $form->textField($questionForm, 'values', array('size' => 5, 'maxlength' => 45)); ?>
            <?php echo $form->error($questionForm, 'values'); ?>
        </div>
    </div>
    <div class="buttons">
        <?php echo CHtml::submitButton('Enregistrer'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->


