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
	$.fn.yiiGridView.update('bloc-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo Yii::t('administration', 'manageQuestionsBlock'); ?></h1>
<div class="info">
    <div class="title"><?php echo Yii::t('questionBlock', 'infoTitle') ?></div>
    <div class="content"><?php echo Yii::t('questionBlock', 'infoContent') ?></div>
</div>
<?php
$imagecreatebloc = CHtml::image(Yii::app()->baseUrl . '/images/page_add.png', 'Créer un nouveau bloc');
echo CHtml::link($imagecreatebloc . Yii::t('administration', 'createBlock'), Yii::app()->createUrl('questionBloc/create'));
?>
<br />
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
    'id' => 'bloc-grid',
    'dataProvider' => $model->search(),
    'columns' => array(
        array('header' => $model->attributeLabels()["title"], 'name' => 'title'),
        array(
            'class' => 'CButtonColumn',
            'afterDelete' => 'function(link,success,data){ if(success) $("#statusMsg").html(data); }',
            'htmlOptions' => array('style' => 'width: 70px')
        ),
    ),
));
?>