<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('user-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Gestion des fiches</h1>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'answers-grid',
    'dataProvider' => $model->search(),
    'columns' => array(
        'name',
        array('header' => 'user', 'value' => '$data->getUserRecorderName()'),
        array('header' => 'Dernière mise à jour', 'value' => '$data->getLastUpdated()'),
        array(
            'class' => 'CButtonColumn',
            'template' => '{view}'
        ),
    ),
));
?>

