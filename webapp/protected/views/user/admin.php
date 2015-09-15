<h1>Gestion des utilisateurs</h1>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'user-grid',
    'dataProvider' => $model->search(),
    'columns' => array(
        'login',
        'nom',
        'prenom',
        'email',
        array(
            'class' => 'CButtonColumn',
        ),
    ),
));
?>