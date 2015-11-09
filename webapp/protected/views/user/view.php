<h1>Utilisateur <?php echo $model->login; ?></h1>

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'login',
        'password',
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
        array(
            'name' => 'Profil(s) actif(s)',
            'type' => 'text',
            'value' => implode(", ", $model->statut)
        ),
        '_id',
    ),
));
?>