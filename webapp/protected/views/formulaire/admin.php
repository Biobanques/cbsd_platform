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
Yii::app()->clientScript->registerScript('getUnchecked', "
       function getUncheckeds(){
            var unch = [];
            $('[name^=Form_id]').not(':checked,[name$=all]').each(function(){unch.push($(this).val());});
            return unch.toString();
       }
       "
);
?>

<h1><?php echo Yii::t('administration', 'forms'); ?></h1>
<div class="info">
    <div class="title"><?php echo Yii::t('form', 'infoTitle') ?></div>
    <div class="content"><?php echo Yii::t('form', 'infoContent') ?></div>
</div>
<?php
$imagecreateform = CHtml::image(Yii::app()->baseUrl . '/images/page_add.png', Yii::t('common', 'createForm'));
echo CHtml::link($imagecreateform . Yii::t('administration', 'createForm'), Yii::app()->createUrl('formulaire/create'));
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
$form = $this->beginWidget('CActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'post',
        ));

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'formulaire-grid',
    'dataProvider' => $model->search(),
    'selectableRows'=>2,
    'beforeAjaxUpdate'=>'function(id,options){options.data={checkedIds:$.fn.yiiGridView.getChecked("formulaire-grid","Form_id").toString(),
        uncheckedIds:getUncheckeds()};
        return true;}',

    'ajaxUpdate'=>true,
    'enablePagination' => true,
    'columns' => array(
        array('class' => 'CCheckBoxColumn', 'id' => 'Form_id', 'checked'=>isset($_GET['ajax']) ? 'Yii::app()->user->getState($data->_id)' : '0'),
        array('header' => $model->attributeLabels()["name"], 'name' => 'name'),
        array(
            'class' => 'CButtonColumn',
            'afterDelete' => 'function(link,success,data){ if(success) $("#statusMsg").html(data); }',
            'htmlOptions' => array('style' => 'width: 70px')
        ),
    ),
));
?>

<div class="row">
    <div class="col-lg-5">
        <?php echo CHtml::submitButton(Yii::t('button', 'deleteSelectedForms'), array('name' => 'rechercher', 'class' => 'btn btn-primary')); ?>
    </div>
</div>
<?php $this->endWidget(); ?>