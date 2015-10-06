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
        array(
            'class' => 'CButtonColumn',
            'buttons' => array(
                'preview' => array(
                    'label' => 'Preview',
                    'url' => 'Yii::app()->controller->createUrl("preview",array("id"=>$data->primaryKey))'
//                    'url' => "'" . $this->createUrl('preview', array('mongoId' => '$data->_id')) . "'"
                )
            ),
            'template' => '{preview}',
            'htmlOptions' => array('style' => 'width: 70px')
        ),
        'title',
        array(
            'class' => 'CButtonColumn',
            'htmlOptions' => array('style' => 'width: 70px')
        ),
    ),
));
?>