<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('audit-trail-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Suivi des actions sur la base</h1>

<?php
$imagesearch = CHtml::image(Yii::app()->baseUrl . '/images/zoom.png', Yii::t('common', 'advancedsearch'));
echo CHtml::link($imagesearch . Yii::t('common', 'advancedsearch'), '#', array('class' => 'search-button'));
?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'audit-trail-grid',
    'type' => 'striped bordered condensed',
    'dataProvider' => $model->search(),
    'columns' => array(
        array('header' => $model->attributeLabels()["action"], 'name' => 'action'),
        array('header' => $model->attributeLabels()["model"], 'name' => 'model'),
        array('header' => $model->attributeLabels()["field"], 'name' => 'field'),
        array('header' => $model->attributeLabels()["stamp"], 'name' => 'stamp', 'value' => '$data->getTimestamp()'),
        array('header' => $model->attributeLabels()["user_id"], 'name' => 'user_id'),
    ),
));
?>
