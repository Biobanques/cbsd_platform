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

<?php echo CHtml::errorSummary($model, null, null, array('class' => 'alert alert-danger')); ?>

<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'questionnaire-form',
        'action' => $this->createUrl('update', array('id' => $model->_id)),
        'enableAjaxValidation' => false,
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
    ));
    ?>

    <br>

    <div>
        <?php
        echo $model->renderTabbedGroup(Yii::app()->language, $model);
        ?>
    </div>

    <hr />
    <div style="display:inline; margin: 35%; width: 100px;">
        <?php
        echo CHtml::submitButton(Yii::t('button', 'saveBtn'), array('class' => 'btn btn-primary'));
        echo CHtml::link(Yii::t('button', 'cancel'), array('answer/affichepatient', 'id' => $model->_id), array('class' => 'btn btn-danger', 'style' => 'margin-top: -5px; margin-left:20px; padding-bottom:5px;'));
        if ($model->type == "genetique") {
            echo CHtml::ajaxSubmitButton(Yii::t('common', 'addGene'), $this->createUrl('updateandadd', array('id' => $model->_id)), array(
                'type' => 'POST',
                'success' => 'js:function(data){'
                . 'div_content = $(data).find("#questionnaire-form");'
                . '$("#questionnaire-form").html(div_content)'
                . '}',
                'error' => 'js:function(xhr, status, error){
                                alert(xhr.responseText);}',
                    ), array('class' => 'btn btn-primary', 'style' => 'margin-left:20px;')
            );
        }
        ?>
    </div>
    <?php
    $this->endWidget();
    ?>
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
            'id' => 'tranche-grid',
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
    <?php if (isset($modelTranche)) { ?>
        <div class="span4 proj-div" data-toggle="modal" data-target="#GSCCModal">Ajouter un échantillon</div>

        <div id="GSCCModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;  </button>
                        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                    </div>
                    <div class="modal-body">
                        <?php
                        $form = $this->beginWidget('CActiveForm', array(
                            'id' => 'trancheUpdate-form',
                            'method' => 'post',
                            'enableAjaxValidation' => false,
                        ));
                        echo $this->renderPartial('_updateTranche', array('modelTranche' => $modelTranche, 'patient' => $patient, 'form' => $form));
                        ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <?php echo CHtml::submitButton(Yii::t('button', 'saveBtn'), array('class' => 'btn btn-primary')); ?>
                        <?php $this->endWidget(); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
