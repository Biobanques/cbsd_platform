<?php
Yii::app()->clientScript->registerScript('answer_view', "
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

<?php if (Yii::app()->user->getState('activeProfil') != "chercheur") { ?>
    <h4>Patient</h4>
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => new CArrayDataProvider(array(get_object_vars($patient))),
        'template' => "{items}",
        'columns' => array(
            array('value' => '$data["id"]', 'name' => 'Patient Id', 'visible' => Yii::app()->user->isAdmin()),
            array('header' => Yii::t('common', 'birthName'), 'value' => '$data["birthName"]'),
            array('header' => Yii::t('common', 'firstName'), 'value' => '$data["firstName"]'),
            array('header' => Yii::t('common', 'birthDate'), 'value' => '$data["birthDate"]')
        ),
    ));
}
?>

<hr />

<h3 align="center">Formulaire <?php echo $model->name; ?></h3>
<p><b>Description: </b><?php echo $model->description; ?></p>

<hr />

<?php
echo CHtml::link(Yii::t('common', 'htmlView'), array('answer/viewOnePage', 'id' => $model->_id));
?>
<div style="margin-top: -20px; text-align:right;">
    <?php
    $img = CHtml::image(Yii::app()->request->baseUrl . '/images/page_white_acrobat.png', 'export as pdf');
    echo CHtml::link(Yii::t('common', 'exportPdf') . $img, array('answer/exportPDF', 'id' => $model->_id), array());
    ?>
</div>

<br />

<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'questionnaire-form',
        'enableAjaxValidation' => false,
    ));
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
$criteria->id_donor = (string) $neuropath->id_donor;
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

<hr />

<div style="display:inline; margin:40%; width: 100px; ">
    <?php
    echo CHtml::link(Yii::t('button', 'back'), array('answer/affichepatient', 'id' => $model->_id), array('class' => 'btn btn-danger', 'style' => 'margin-top: -15px;margin-left:20px;'));
    if (Yii::app()->user->isAuthorizedUpdate(Yii::app()->user->getState('activeProfil'), $model->type)) {
        echo CHtml::link(Yii::t('button', 'updateThePatientForm'), array('answer/update', 'id' => $model->_id), array('class' => 'btn btn-primary', 'style' => 'margin-top: -15px;margin-left:10px;'));
    }
    ?>

    <?php $this->endWidget(); ?>
    
</div>
