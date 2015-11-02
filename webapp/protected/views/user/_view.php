<div class="view">

    <b><?php echo CHtml::encode($data->getAttributeLabel('_id')); ?>:</b>
    <?php echo CHtml::link(CHtml::encode($data->_id), array('view', 'id' => $data->_id)); ?>
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

</div>