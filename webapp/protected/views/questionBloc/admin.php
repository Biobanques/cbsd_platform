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

<h1>Gestion des blocs</h1>

<?php echo CHtml::link('Créer un nouveau bloc', Yii::app()->createUrl('questionBloc/create')); ?>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'questionnaires-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'title',
        array(
            'class' => 'CButtonColumn',
            'htmlOptions' => array('style' => 'width: 70px')
        ),
    ),
));
?>