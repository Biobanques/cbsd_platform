<h1><?php echo Yii::t('common', 'manageRules'); ?></h1>

<h3>Fiche Clinique</h3>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'dataProvider' => $dataProviderClinique,
    'columns' => array(
        array('header' => $model->attributeLabels()["profil"], 'name' => 'profil'),
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
        array('header' => $model->attributeLabels()["profil"], 'name' => 'profil'),
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
        array('header' => $model->attributeLabels()["profil"], 'name' => 'profil'),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}',
        ),
    ),
));
?>