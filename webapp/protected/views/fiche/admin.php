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
<?php echo CHtml::link('Recherche avancée', '#', array('class' => 'search-button')); ?>
<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->
<br>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'fiche-grid',
    'type' => 'striped bordered condensed',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array('header' => $model->attributeLabels()["name"], 'name' => 'name', 'value' => '$data->name'),
        array('header' => $model->attributeLabels()["user"], 'name' => 'user', 'value' => '$data->getUserRecorderName()'),
        array('header' => $model->attributeLabels()["last_updated"], 'name' => 'last_updated', 'value' => '$data->getLastUpdated()'),
        array(
            'class' => 'CButtonColumn',
            'template' => '{view}'
        ),
    ),
));
?>