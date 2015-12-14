<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('formulaire-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Gestion des formulaires</h1>

<?php echo CHtml::link('Créer un nouveau formulaire', Yii::app()->createUrl('formulaire/create')); ?>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'dataProvider' => $model->search(),
    'columns' => array(
        array('header' => $model->attributeLabels()["name"], 'name' => 'name', 'value' => '$data->name'),
        array(
            'class' => 'CButtonColumn',
            'htmlOptions' => array('style' => 'width: 70px')
        ),
    ),
));
?>

