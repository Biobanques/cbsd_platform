<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'login'); ?>
		<?php echo $form->textField($model,'login'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'profil'); ?>
		<?php echo $form->textField($model,'profil'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'nom'); ?>
		<?php echo $form->textField($model,'nom'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'prenom'); ?>
		<?php echo $form->textField($model,'prenom'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'email'); ?>
		<?php echo $form->textField($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'telephone'); ?>
		<?php echo $form->textField($model,'telephone'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'gsm'); ?>
		<?php echo $form->textField($model,'gsm'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'inactif'); ?>
		<?php echo $form->textField($model,'inactif'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'verifyCode'); ?>
		<?php echo $form->textField($model,'verifyCode'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'_id'); ?>
		<?php echo $form->textField($model,'_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->