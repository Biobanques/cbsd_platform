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
    $.fn.yiiGridView.update('fileImport-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
?>

<h1><?php echo "Historique des fichiers importés"; ?></h1>

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
    'id' => 'fileImport-grid',
    'type' => 'striped bordered condensed',
    'dataProvider' => $model->search(),
    'columns' => array(
        array('header' => $model->attributeLabels()["user"], 'name' => 'user', 'value' => '$data->getUserRecorderName()'),
        array('header' => $model->attributeLabels()["filename"], 'name' => 'filename'),
        array('header' => $model->attributeLabels()["filesize"], 'name' => 'filesize', 'value' => '$data->formatSizeUnits($data["filesize"])'),
        array('header' => $model->attributeLabels()["extension"], 'name' => 'extension'),
        array('header' => $model->attributeLabels()["date_import"], 'name' => 'date_import', 'value' => '$data->getDateImport()'),
    ),
));
?>

<script>
function datePicker(clicked) {
    $('input[name="' + clicked + '"]').daterangepicker({
        "applyClass": "btn-primary",
        "showDropdowns": true,
        locale: {
            format: "DD/MM/YYYY",
            applyLabel: 'Valider',
            cancelLabel: 'Effacer'
        }
    });
    $('input[name="' + clicked + '"]').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
    });
}
</script>