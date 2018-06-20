<?php
Yii::app()->clientScript->registerScript('formulaire_view', "
$(document).ready(function() {
    $(window).scrollTop($('#titleForm').offset().top).scrollLeft($('#titleForm').offset().left);
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

<h3 id="titleForm" align="center"><?php echo Yii::t('administration', 'form') . $model->name; ?></h3>
<p><b>Description: </b><?php echo $model->description; ?></p>
<?php
if ($model->last_modified != null && $model->last_modified != "") {
    $q = Questionnaire::model()->findByPk(new MongoId($_GET['id']));
    echo "<p><b>" . Yii::t('common', 'lastModifiedDate') . ": </b>" . date(CommonTools::FRENCH_SHORT_DATE_FORMAT, strtotime($q->last_modified['date'])) . "</p>";
}
?>
<p><b><?php echo Yii::t('common', 'createdBy') ?>: </b><?php echo $model->creator; ?></p>
<hr />

<?php echo CHtml::errorSummary($model, null, null, array('class' => 'alert alert-danger')); ?>
<div>
    <?php
    echo $model->renderTabbedGroup(Yii::app()->language);
    ?>
</div>
<hr />
<div style="display:inline; margin:40%; width: 100px; ">
    <?php
    echo CHtml::link(Yii::t('button', 'back'), array('formulaire/admin'), array('class' => 'btn btn-primary'));
    ?>
</div>