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

<h1><?php echo Yii::t('administration', 'referenceCenter'); ?></h1>
<div class="info">
    <div class="title"><?php echo Yii::t('common', 'referenceTitle') ?></div>
    <div class="content"><?php echo Yii::t('common', 'referenceContent') ?></div>
</div>
<?php
$imagecreateuser = CHtml::image(Yii::app()->baseUrl . '/images/page_add.png', Yii::t('administration', 'createCenter'));
echo CHtml::link($imagecreateuser . Yii::t('administration', 'createCenter'), array('referenceCenter/create'));
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'post',
        ));

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'referenceCenter-grid',
    'dataProvider' => $model->search(),
    'columns' => array(
        array('id' => 'ReferenceCenter_id', 'value' => '$data->_id', 'class' => 'CCheckBoxColumn', 'selectableRows' => 2),
        array('header' => $model->attributeLabels()["center"], 'name' => 'center'),
        array(
            'class' => 'CButtonColumn',
            'template' => '{update}{delete}',
            'afterDelete' => 'function(link,success,data){ if(success) $("#statusMsg").html(data); }',
            'htmlOptions' => array('style' => 'width: 70px')
        ),
    ),
));
?>

<div class="row">
    <div class="col-lg-5">
        <?php echo CHtml::submitButton(Yii::t('button', 'deleteSelectedReferences'), array('name' => 'rechercher', 'class' => 'btn btn-primary')); ?>
    </div>
</div>
<?php $this->endWidget(); ?>