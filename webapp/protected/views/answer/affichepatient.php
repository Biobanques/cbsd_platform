<?php
$this->pageTitle = Yii::app()->name . ' - Affiche patient';
$this->breadcrumbs = array(
    'Affichepatient',
);
?>

<p><?php echo Yii::app()->user->name ?>, voici les formulaires dont vous disposez pour ce patient.</p>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'dataProvider' => new CArrayDataProvider(array($model->getAttributes())),
    'template' => "{items}",
    'columns' => array(
        array('value' => '$data["id"]', 'name' => 'Patient Id'),
        array('value' => '$data["nom"]', 'header' => 'nom'),
        array('value' => '$data["nom_naissance"]', 'header' => 'nom de naissance'),
        array('value' => '$data["prenom"]', 'header' => 'prenom'),
        array('value' => '$data["date_naissance"]', 'header' => 'Date de naissance'),
    ),
));
?>

<p> Formulaires patient renseignés : </p>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'dataProvider' => $dataProvider,
    'template' => "{items}",
    'columns' => array(
        array('name' => 'id', 'header' => 'Identifiant de la fiche'),
        array('name' => 'Date de modification', 'value' => '$data->getLastModified()'),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'htmlOptions' => array('style' => 'width: 50px'),
        ),
    ),
));
?>