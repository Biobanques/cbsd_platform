<div id="statusMsg">
    <?php if (Yii::app()->user->hasFlash('success')) { ?>
        <div class="flash-success">
            <?php echo Yii::app()->user->getFlash('success'); ?>
        </div>
    <?php } ?>

    <?php if (Yii::app()->user->hasFlash('error')) { ?>
        <div class="flash-error">
            <?php echo Yii::app()->user->getFlash('error'); ?>
        </div>
    <?php } ?>
</div>

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

<h1><?php echo Yii::t('common', 'manageForms'); ?></h1>

<?php
$imagecreateform = CHtml::image(Yii::app()->baseUrl . '/images/page_add.png', Yii::t('common', 'createForm'));
echo CHtml::link($imagecreateform . Yii::t('common', 'createForm'), Yii::app()->createUrl('formulaire/create'));
?>
<br />
<?php
$imagesearch = CHtml::image(Yii::app()->baseUrl . '/images/zoom.png', Yii::t('common', 'advancedsearch'));
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
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'formulaire-grid',
    'type' => 'striped bordered condensed',
    'dataProvider' => $model->search(),
    'columns' => array(
        array('header' => $model->attributeLabels()["name"], 'name' => 'name'),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'afterDelete' => 'function(link,success,data){ if(success) $("#statusMsg").html(data); }',
            'htmlOptions' => array('style' => 'width: 70px')
        ),
    ),
));
?>

