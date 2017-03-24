<div id="statusMsg">
    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="flash-success">
            <?php echo Yii::app()->user->getFlash('success'); ?>
        </div>
    <?php endif; ?>

    <?php if (Yii::app()->user->hasFlash('error')): ?>
        <div class="flash-error">
            <?php echo Yii::app()->user->getFlash('error'); ?>
        </div>
    <?php endif; ?>
</div>

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

<h1><?php echo Yii::t('administration', 'managePatientForm'); ?></h1>
<div class="info">
    <div class="title"><?php echo Yii::t('patientForm', 'infoTitle') ?></div>
    <div class="content"><?php echo Yii::t('patientForm', 'infoContent') ?></div>
</div>
<?php
$imagesearch = CHtml::image(Yii::app()->baseUrl . '/images/zoom.png', Yii::t('administration', 'advancedsearch'));
echo CHtml::link($imagesearch . Yii::t('common', 'advancedsearch'), '#', array('class' => 'search-button'));
?>
<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'fiche-grid',
    'dataProvider' => $model->search(),
    'columns' => array(
        array('header' => $model->attributeLabels()["name"], 'name' => 'name'),
        array('header' => $model->attributeLabels()["user"], 'name' => 'user', 'value' => '$data->getUserRecorderName()'),
        array('header' => $model->attributeLabels()["last_updated"], 'name' => 'last_updated', 'value' => '$data->getLastUpdated()'),
        array(
            'class' => 'CButtonColumn',
            'template' => '{view}{delete}',
            'afterDelete' => 'function(link,success,data){ if(success) $("#statusMsg").html(data); }'
        ),
    ),
));
?>