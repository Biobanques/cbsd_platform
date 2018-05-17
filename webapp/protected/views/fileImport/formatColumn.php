<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('columnFilemaker-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
?>

<div id="statusMsg">
    <?php
    if (!Yii::app()->user->hasFlash('success')) {
        echo Yii::app()->user->getFlash('success');
    }
    if (!Yii::app()->user->hasFlash('error')) {
        echo Yii::app()->user->getFlash('error');
    }
    ?>
</div>

<h1><?php echo Yii::t('administration', 'columnFileMaker'); ?></h1>
<?php
$imagecreateColumn = CHtml::image(Yii::app()->baseUrl . '/images/page_add.png', Yii::t('administration', 'createColumn'));
echo CHtml::link($imagecreateColumn . Yii::t('administration', 'createColumn'), array('fileImport/create'));
?>
<br />
<?php
/*$imagesearch = CHtml::image(Yii::app()->baseUrl . '/images/zoom.png', Yii::t('administration', 'advancedsearch'));
echo CHtml::link($imagesearch . Yii::t('common', 'advancedsearch'), '#', array('class' => 'search-button'));*/
?>
<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_searchColumn', array(
        'modelColumn' => $modelColumn,
    ));
    ?>
</div><!-- search-form -->

<?php
$form = $this->beginWidget('CActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'post',
        ));

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'columnFilemaker-grid',
    'dataProvider' => $modelColumn->search(),
    'columns' => array(
        array('id' => 'ColumnFileMaker_id', 'value' => '$data->_id', 'class' => 'CCheckBoxColumn', 'selectableRows' => 2),
        array('header' => $modelColumn->attributeLabels()["currentColumn"], 'name' => 'currentColumn'),
        array('header' => $modelColumn->attributeLabels()["newColumn"], 'name' => 'newColumn'),
        array('header' => $modelColumn->attributeLabels()["type"], 'name' => 'type', 'value' => '$data->getType()'),
        array('header' => $modelColumn->attributeLabels()["values"], 'name' => 'values', 'value' => '$data->getValues()'),
        array(
            'class' => 'CButtonColumn',
            'template' => '{update}{delete}',
            'afterDelete' => 'function(link,success,data){ if(success) $("#statusMsg").html(data); }'
        ),
    ),
));
?>

<div class="row">
    <div class="col-lg-5">
        <?php echo CHtml::submitButton(Yii::t('button', 'deleteSelectedDatas'), array('name' => 'rechercher', 'class' => 'btn btn-primary')); ?>
    </div>
</div>
<?php $this->endWidget(); ?>

<h1>Prélèvements</h1>

<?php
$imagecreatePrvmt = CHtml::image(Yii::app()->baseUrl . '/images/page_add.png', Yii::t('administration', 'createPrelevement'));
echo CHtml::link($imagecreatePrvmt . Yii::t("administration", 'createPrelevement'), array('fileImport/createPrvmt'));
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'post',
        ));

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'prelevement-grid',
    'dataProvider' => $prelevement->search(),
    'columns' => array(
        array('id' => 'Prelevement_id', 'value' => '$data->_id', 'class' => 'CCheckBoxColumn', 'selectableRows' => 2),
        array('header' => $prelevement->attributeLabels()["currentColumn"], 'name' => 'currentColumn'),
        array('header' => $prelevement->attributeLabels()["newColumn"], 'name' => 'newColumn'),
        array('header' => $prelevement->attributeLabels()["type"], 'name' => 'type', 'value' => '$data->getType()'),
        array('header' => $prelevement->attributeLabels()["values"], 'name' => 'values', 'value' => '$data->getValues()'),
        array(
            'class' => 'CButtonColumn',
            'template' => '{update}{delete}',
            'afterDelete' => 'function(link,success,data){ if(success) $("#statusMsg").html(data); }'
        ),
    ),
));
?>

<div class="row">
    <div class="col-lg-5">
        <?php echo CHtml::submitButton(Yii::t('button', 'deleteSelectedDatas'), array('name' => 'rechercher', 'class' => 'btn btn-primary')); ?>
    </div>
</div>
<?php $this->endWidget(); ?>