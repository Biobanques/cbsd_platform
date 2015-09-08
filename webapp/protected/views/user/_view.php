<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->_id), array('view', 'id'=>$data->_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('login')); ?>:</b>
	<?php echo CHtml::encode($data->login); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('password')); ?>:</b>
	<?php echo CHtml::encode($data->password); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('profil')); ?>:</b>
	<?php echo CHtml::encode($data->profil); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nom')); ?>:</b>
	<?php echo CHtml::encode($data->nom); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('prenom')); ?>:</b>
	<?php echo CHtml::encode($data->prenom); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('telephone')); ?>:</b>
	<?php echo CHtml::encode($data->telephone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('gsm')); ?>:</b>
	<?php echo CHtml::encode($data->gsm); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('inactif')); ?>:</b>
	<?php echo CHtml::encode($data->inactif); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cbsdforms_id')); ?>:</b>
	<?php echo CHtml::encode($data->cbsdforms_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('verifyCode')); ?>:</b>
	<?php echo CHtml::encode($data->verifyCode); ?>
	<br />

	*/ ?>

</div>