<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'questionnaireBloc-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><?php echo Yii::t('common','ChampsObligatoires'); ?></p>

	<?php 
        
        echo $form->errorSummary($model,null,null,array('class'=>'alert alert-error')); ?>
        
	<div class="row">
            <p>L'id permet de repérer rapidement l'onglet dans l arborescence de questions.</p>
		<?php echo $form->labelEx($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>5,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'id'); ?>
	</div>
    <div class="row">
        <p>Le titre est le libellé affiché de l'onglet.</p>
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>5,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
	<div class="row">
            <p>Le bloc regroupe un groupe de questions.</p>
		<?php echo $form->labelEx($model,'parent_group'); ?>
		<?php echo $form->textField($model,'parent_group',array('size'=>5,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'parent_group'); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton('Enregistrer'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->