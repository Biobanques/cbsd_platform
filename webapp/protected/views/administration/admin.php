<h1>Gestion des droits</h1>

<h3>Fiche Clinique</h3>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'dataProvider' => $dataProviderClinique,
    'columns' => array(
        'profil',
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}',
        ),
    ),
));
?>

<hr />

<h3>Fiche Neuropathologique</h3>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'dataProvider' => $dataProviderNeuropath,
    'columns' => array(
        'profil',
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}',
        ),
    ),
));
?>

<hr />

<h3>Fiche Génétique</h3>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'dataProvider' => $dataProviderGene,
    'columns' => array(
        'profil',
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}',
        ),
    ),
));
?>