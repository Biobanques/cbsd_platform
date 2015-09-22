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

<h1>Gestion des formulaires</h1>

<?php echo CHtml::link('Créer un nouveau formulaire', Yii::app()->createUrl('formulaire/create')); ?>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'questionnaires-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'name',
        array(
            'class' => 'CButtonColumn',
        ),
    ),
));
?>

