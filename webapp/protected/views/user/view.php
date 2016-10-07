<h1>Utilisateur <?php echo $model->login; ?></h1>

<?php
$this->widget('bootstrap.widgets.TbDetailView', array(
    'data' => $model,
    'attributes' => array(
        'login',
        array(
            'name' => 'profil',
            'type' => 'text',
            'value' => implode(", ", $model->profil)
        ),
        'nom',
        'prenom',
        'email',
        'telephone',
        'gsm',
        'address',
        'centre',
        '_id',
    ),
));
?>

<div style="display:inline; margin:40%; width: 100px; ">
    <?php
    echo CHtml::link('Retour', array('user/admin'), array('class' => 'btn btn-default'));
    ?>
</div>