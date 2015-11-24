<h1>Utilisateur <?php echo $model->login; ?></h1>

<?php
$this->widget('zii.widgets.CDetailView', array(
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