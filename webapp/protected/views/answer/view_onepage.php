<?php
Yii::app()->clientScript->registerScript('answer_viewonepage', "
$(document).ready(function() {
    var inputs = document.getElementsByTagName('input');
    var textareas = document.getElementsByTagName('textarea');
    var selectlist = document.getElementsByTagName('select');
    var len_inputs = inputs.length;
    var len_textareas = textareas.length;
    var len_selectlist = selectlist.length;

    for (var i = 0; i < len_inputs; i++) {
        inputs[i].disabled = true;
    }
    for (var i = 0; i < len_textareas; i++) {
        textareas[i].disabled = true;
    }
    for (var i = 0; i < len_selectlist; i++) {
        selectlist[i].disabled = true;
    }
});
");
?>

<h3 align="center">Vue HTML du formulaire <?php echo $model->name; ?></h3>
<?php echo CHtml::link('Vue standard', array('answer/view', 'id' => $model->_id)); ?>
<div style="margin-top: -20px; text-align:right;">
    <?php
    $img = CHtml::image(Yii::app()->request->baseUrl . '/images/page_white_acrobat.png', 'export as pdf');
    echo CHtml::link('Exporter au format PDF' . $img, array('answer/exportPDF', 'id' => $model->_id), array());
    ?>
</div>
<?php echo $model->renderHTML(Yii::app()->language); ?>
