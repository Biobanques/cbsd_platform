<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('bloc-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Gestion des blocs</h1>

<?php echo CHtml::link('Créer un nouveau bloc', Yii::app()->createUrl('questionBloc/create')); ?>
<br />
<?php echo CHtml::link('Recherche avancée', '#', array('class' => 'search-button')); ?>
<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'bloc-grid',
    'type' => 'striped bordered condensed',
    'dataProvider' => $model->search(),
    'columns' => array(
        array('header' => $model->attributeLabels()["title"], 'name' => 'title', 'value' => '$data->title'),
        array(
            'class' => 'CButtonColumn',
            'template' => '{update}{delete}',
            'htmlOptions' => array('style' => 'width: 70px')
        ),
    ),
));
?>