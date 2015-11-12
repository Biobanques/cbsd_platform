<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('fiche-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Gestion des fiches</h1>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'fiche-grid',
    'type' => 'striped bordered condensed',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array('header' => 'Nom de la fiche', 'value' => '$data->getFicheName()', 'filter' => CHtml::activeTextField($model, 'name')),
        array('header' => 'Utilisateur', 'value' => '$data->getUserRecorderName()', 'filter' => CHtml::activeTextField($modelUser, 'nom')),
        array('header' => 'Dernière mise à jour', 'value' => '$data->getLastUpdated()'),
        array(
            'class' => 'CButtonColumn',
            'template' => '{view}'
        ),
    ),
));
?>