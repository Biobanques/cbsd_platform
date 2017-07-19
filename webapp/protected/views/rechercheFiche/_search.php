<?php

Yii::app()->clientScript->registerScript('searchView', "
$('#selectCas').click(function(){
    if ($('#Answer_id_patient :selected').length > 0) {
        $('#selectCas').attr('disabled',true);
        $('#selection').append('<p id=\"CasSelected\">- Cas sélectionnés</p><br>');
    }
    return false;
});
$('#selectForm').click(function(){
    if ($('#Answer_type :selected').length > 0) {
        $('#selectForm').attr('disabled',true);
        $('#selection').append('<p id=\"FormSelected\">- Formulaires sélectionnés</p><br>');
    }
    return false;
});
$('#selectDate').click(function(){
    if ($('#Answer_last_updated').val().length > 0) {
        $('#selectDate').attr('disabled',true);
        $('#selection').append('<p id=\"DateSelected\">- Période sélectionnée</p><br>');
    }
    return false;
});

$('#Answer_id_patient').change(function(){
    if ($('#Answer_id_patient :selected').length == 0) {
        $('#selectCas').attr('disabled',false);
        $('#CasSelected').remove();
    }
    return false;
});

$('#Answer_type').change(function(){
    if ($('#Answer_type :selected').length == 0) {
        $('#selectForm').attr('disabled',false);
        $('#FormSelected').remove();
    }
    return false;
});

$('#resetCas').click(function(){
    $('#Answer_id_patient').val('0');
    $('#selectCas').attr('disabled',false);
    $('#CasSelected').remove();
    return false;
});
$('#resetForm').click(function(){
    $('#Answer_type').val('0');
    $('#selectForm').attr('disabled',false);
    $('#FormSelected').remove();
    return false;
});
$('#resetDate').click(function(){
    $('#Answer_last_updated') = '';
    $('#selectDate').attr('disabled',false);
    $('#DateSelected').remove();
    return false;
});
");
?>


<div class="wide form">
    <p>*<?php echo Yii::t('common', 'search1') ?></p>
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'light_search-form',
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'post',
    ));
    ?>

    <div style="border:1px solid black;">

        <h4 style="margin-left:10px;"><u><b><?php echo Yii::t('common', 'queryAnonymous') ?></b></u></h4>

        <div class="row">
            <div class="col-lg-12">
                <?php echo CHtml::label(Yii::t('common', 'individualSelection'), 'Answer_id_patient', array('style' => 'width:250px')); ?>
                <?php echo $form->dropDownList($model, 'id_patient', Answer::model()->getIdPatientFiches(), array("multiple" => "multiple", "onclick" => "restrictQuery()")); ?>
                <?php echo CHtml::submitButton('Sélectionner', array('id' => 'selectCas', 'class' => 'btn btn-success')); ?>
                <?php echo CHtml::resetButton('Réinitialiser', array('id' => 'resetCas', 'class' => 'btn btn-danger')); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <?php echo CHtml::label(Yii::t('common', 'restrictQuery'), 'Answer_type', array('style' => 'width:250px')); ?>
                <?php echo $form->dropDownList($model, 'type', Questionnaire::model()->getArrayType(), array("multiple" => "multiple", "onclick" => "restrictQuery()")); ?>
                <?php echo CHtml::submitButton('Sélectionner', array('id' => 'selectForm', 'class' => 'btn btn-success')); ?>
                <?php echo CHtml::resetButton('Réinitialiser', array('id' => 'resetForm', 'class' => 'btn btn-danger')); ?>
            </div>
        </div>

        <div class ="row">
            <div class="col-lg-12">
                <?php echo CHtml::label(Yii::t('common', 'restrictPeriod'), 'Answer_last_updated', array('style' => 'width:250px')); ?>
                <?php echo $form->textField($model, 'last_updated', array("onfocus" => "datePicker(this.name)")); ?>
                <?php echo CHtml::submitButton('Sélectionner', array('id' => 'selectDate', 'class' => 'btn btn-success')); ?>
                <?php echo CHtml::resetButton('Réinitialiser', array('id' => 'resetDate', 'class' => 'btn btn-danger')); ?>
            </div>
        </div>

        <p style="margin-left:10px;"><?php echo Yii::t('common', 'notRestrict'); ?></p>

    </div>
    
    <div class="well">
        <p id="selection"> Pas de sélection. </p>
    </div>

    <div class="row buttons">
        <div class="col-lg-7 col-lg-offset-7">
            <?php echo CHtml::submitButton('Suivant', array('id' => 'next', 'class' => 'btn btn-primary')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->