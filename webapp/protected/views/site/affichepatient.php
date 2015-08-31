<?php

$this->pageTitle=Yii::app()->name . ' - Affiche patient';
$this->breadcrumbs=array(
	'Affichepatient',
);
?>

<p><?php echo Yii::app()->user->name ?>, voici les formulaires dont vous disposez pour ce patient.</p>

<?php
 $this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped bordered condensed',
    'dataProvider'=>$dataProvider,
    'template'=>"{items}",
    'columns'=>array(
        array('name'=>'id', 'header'=>'Patient Id'),
        array('name'=>'nom', 'header'=>'nom'),
        array('name'=>'prenom', 'header'=>'prenom'),
        array('name'=>'date_naissance', 'header'=>'Date de naissance'),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'htmlOptions'=>array('style'=>'width: 50px'),
            'template'=>'{view}{update}'
        ),
    ),
)); ?>

<p> Formulaires patient renseignés : </p>

<?php
 $this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped bordered condensed',
    'dataProvider'=>$dataProvider,
    'template'=>"{items}",
    'columns'=>array(
        array('name'=>'id', 'header'=>'Identifiant de la fiche'),
        array('name'=>'Date de modification', 'value'=>'$data->getLastModified()'),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'htmlOptions'=>array('style'=>'width: 50px'),
        ),
    ),
)); ?>