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
    'id' => 'formulaire-grid',
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

