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
    $.fn.yiiGridView.update('user-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
?>

<h1><?php echo Yii::t('administration', 'manageProjects'); ?></h1>

<?php
$imagesearch = CHtml::image(Yii::app()->baseUrl . '/images/zoom.png', Yii::t('administration', 'advancedsearch'));
echo CHtml::link($imagesearch . Yii::t('common', 'advancedsearch'), '#', array('class' => 'search-button'));
?>
<div class="search-form" style="display:none">
    <?php
    /* $this->renderPartial('_search', array(
      'model' => $model,
      )); */
    ?>
</div><!-- search-form -->

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'user-grid',
    'dataProvider' => $model->search(),
    'columns' => array(
        array('header' => $model->attributeLabels()["user"], 'name' => 'user'),
        array('header' => $model->attributeLabels()["project_name"], 'name' => 'project_name'),
        array('header' => $model->attributeLabels()["file"], 'name' => 'file'),
        array('header' => $model->attributeLabels()["project_date"], 'name' => 'project_date', 'value' => '$data->getDateProject()'),
        array(
            'class' => 'CButtonColumn',
            'template' => '{import}{delete}',
            'buttons' => array(
                'import' => array(
                    'label' => 'Import CSV file',
                    'imageUrl' => Yii::app()->request->baseUrl . '/images/page_white_csv.png',
                    'url'=>'Yii::app()->createUrl("project/import", array("project_file"=>$data->file))'
                )
            ),
        ),
    )
));