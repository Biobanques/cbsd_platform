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

<h1>Colonne Filemaker</h1>
<?php
echo CHtml::link('Créer une colonne', array('fileImport/create'));
?>
<br />
<?php
$imagesearch = CHtml::image(Yii::app()->baseUrl . '/images/zoom.png', Yii::t('common', 'advancedsearch'));
echo CHtml::link($imagesearch . Yii::t('common', 'advancedsearch'), '#', array('class' => 'search-button'));
?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'columnFilemaker-grid',
    'dataProvider' => $model->search(),
    'columns' => array(
        array('header' => $model->attributeLabels()["currentColumn"], 'name' => 'currentColumn'),
        array('header' => $model->attributeLabels()["newColumn"], 'name' => 'newColumn'),
        array('header' => $model->attributeLabels()["type"], 'name' => 'type')
    ),
));
?>