<?php
Yii::app()->clientScript->registerScript('fiche_view', "
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

<h3 align="center"><?php echo Yii::t('administration', 'patientForm') . $model->name; ?></h3>
<p><b>Description: </b><?php echo $model->description; ?></p>
<?php
if ($model->last_modified != null && $model->last_modified != "") {
    echo "<p><b>" . Yii::t('common', 'lastUpdated') . "</b>" . $model->getLastUpdated() . "</p>";
}
?>
<p><b> <?php echo Yii::t('common', 'createdBy') ?>: </b><?php echo $model->creator; ?></p>
<hr />
<?php
echo CHtml::link(Yii::t('common', 'htmlView'), array('fiche/viewOnePage', 'id' => $model->_id));
?>
<div style="margin-top: -15px; text-align:right;">
    <?php
    $img = CHtml::image(Yii::app()->request->baseUrl . '/images/page_white_acrobat.png', Yii::t('common', 'exportPdf'));
    echo CHtml::link(Yii::t('common', 'exportPdf') . $img, array('answer/exportPDF', 'id' => $model->_id), array());
    ?>
</div>

<?php echo CHtml::errorSummary($model, null, null, array('class' => 'alert alert-danger')); ?>
<div>
    <?php
    echo $model->renderTabbedGroup(Yii::app()->language);
    ?>
</div>

<hr />
<?php
if ($model->name == "Import Neuropath") {
    echo "<h3 style=\"text-align:center;\" >Prélèvement Tissue Tranche</h3>";
$modelTranche = new Tranche;
$neuropath = Neuropath::model()->findByAttributes(array("id_cbsd" => (int) $model->id_patient));
$criteria = new EMongoCriteria();
$criteria->id_donor = $neuropath->id_donor;
$dataProvider = new EMongoDocumentDataProvider('Tranche', array('criteria' => $criteria));
?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProvider,
    'columns' => array(
        array('header' => $modelTranche->attributeLabels()["presenceCession"], 'name' => 'presenceCession'),
        array('header' => $modelTranche->attributeLabels()["hemisphere"], 'name' => 'hemisphere'),
        array('header' => $modelTranche->attributeLabels()["idPrelevement"], 'name' => 'idPrelevement'),
        array('header' => $modelTranche->attributeLabels()["nameSamplesTissue"], 'name' => 'nameSamplesTissue'),
        array('header' => $modelTranche->attributeLabels()["originSamplesTissue"], 'name' => 'originSamplesTissue'),
        array('header' => $modelTranche->attributeLabels()["prelevee"], 'name' => 'prelevee'),
        array('header' => $modelTranche->attributeLabels()["nAnonymat"], 'name' => 'nAnonymat'),
        array('header' => $modelTranche->attributeLabels()["qualite"], 'name' => 'qualite'),
        array('header' => $modelTranche->attributeLabels()["quantityAvailable"], 'name' => 'quantityAvailable'),
        array('header' => $modelTranche->attributeLabels()["storageConditions"], 'name' => 'storageConditions')
    ),
));
}
?>

<div style="display:inline; margin:40%; width: 100px; ">
    <?php
    echo CHtml::link(Yii::t('button', 'back'), array('fiche/admin'), array('class' => 'btn btn-primary'));
    ?>
</div>