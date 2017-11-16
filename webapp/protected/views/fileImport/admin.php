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
$('.import-button').click(function(){
    $('.import-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('fileImport-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
?>

<h1><?php echo Yii::t('administration', 'historyImport'); ?></h1>
<div class="info">
    <div class="title"><?php echo Yii::t('uploadFileMaker', 'infoTitle') ?></div>
    <div class="content"><?php echo Yii::t('uploadFileMaker', 'infoContent') ?></div>
</div>
<?php
$importFileMaker = CHtml::image(Yii::app()->baseUrl . '/images/database_add.png', Yii::t('common', 'importFileMaker'));
echo CHtml::link($importFileMaker . Yii::t('administration', 'importFileMaker'), array('uploadedFile/admin'), array('class' => 'import-button'));
?>
<div class="import-form" style="display:none">
    <?php
    $this->renderPartial('_import', array(
        'uploadedFile' => $uploadedFile,
    ));
    ?>
</div><!-- import-form -->

<div style="clear:both"></div>

<?php
/*$imagesearch = CHtml::image(Yii::app()->baseUrl . '/images/zoom.png', Yii::t('administration', 'advancedsearch'));
echo CHtml::link($imagesearch . Yii::t('common', 'advancedsearch'), '#', array('class' => 'search-button'));*/
?>
<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->

<?php
$form = $this->beginWidget('CActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'post',
        ));

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'fileImport-grid',
    'dataProvider' => $model->search(),
    'columns' => array(
        array('id' => 'FileImport_id', 'value' => '$data->_id', 'class' => 'CCheckBoxColumn', 'selectableRows' => 2),
        array('header' => $model->attributeLabels()["user"], 'name' => 'user', 'value' => 'CommonTools::getUserLogin()'),
        array('header' => $model->attributeLabels()["filename"], 'name' => 'filename'),
        array('header' => $model->attributeLabels()["filesize"], 'name' => 'filesize', 'value' => 'CommonTools::formatSizeUnits($data["filesize"])'),
        array('header' => $model->attributeLabels()["extension"], 'name' => 'extension'),
        array('header' => $model->attributeLabels()["date_import"], 'name' => 'date_import', 'value' => '$data->getDateImport()'),
        array('header' => $model->attributeLabels()["imported"], 'name' => 'imported'),
        /*array(
            'class' => 'CLinkColumn',
            'labelExpression' => '$data->getNonImportedNumber()',
            'urlExpression' => 'Yii::app()->createUrl("fileImport/exportNonImported",array("id"=>$data->_id))',
            'htmlOptions' => array('style' => "text-align:center"),
            'header' => $model->attributeLabels()["not_imported"]
        )*/
    ),
));
?>

<div class="row">
    <div class="col-lg-5">
        <?php echo CHtml::submitButton(Yii::t('button', 'deleteSelectedImportedFiles'), array('name' => 'rechercher', 'class' => 'btn btn-primary')); ?>
    </div>
</div>
<?php $this->endWidget(); ?>