<?php
Yii::app()->clientScript->registerScript('search', "
$('#selectCas').click(function(){
    if ($('#Answer_id_patient :selected').length > 0) {
        $('#selectCas').attr('disabled',true);
        if (document.getElementById('selection').innerText == 'Pas de sélection.') {
            $('#selection').html('<p id=\"CasSelected\">- Cas sélectionnés: ' + $('#Answer_id_patient').val() + '</p><br>');
        } else {
            $('#selection').append('<p id=\"CasSelected\">- Cas sélectionnés: ' + $('#Answer_id_patient').val() + '</p><br>');
        }
    }
    return false;
});
$('#selectForm').click(function(){
    if ($('#Answer_type :selected').length > 0) {
        $('#selectForm').attr('disabled',true);
        if (document.getElementById('selection').innerText == 'Pas de sélection.') {
            $('#selection').html('<p id=\"FormSelected\">- Formulaires sélectionnés: ' + $('#Answer_type').val() + '</p><br>');
        } else {
            $('#selection').append('<p id=\"FormSelected\">- Formulaires sélectionnés: ' + $('#Answer_type').val() + '</p><br>');
        }
    }
    return false;
});
$('#selectDate').click(function(){
    if ($('#Answer_last_updated').val().length > 0) {
        $('#selectDate').attr('disabled',true);
        if (document.getElementById('selection').innerText == 'Pas de sélection.') {
            $('#selection').html('<p id=\"DateSelected\">- Période sélectionnée: ' + $('#Answer_last_updated').val() + '</p><br>');
        } else {
            $('#selection').append('<p id=\"DateSelected\">- Période sélectionnée: ' + $('#Answer_last_updated').val() + '</p><br>');
            }
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
    if ($('#selection').text().length == 0) {
        $('#selection').html('<p id=\"selection\">Pas de sélection.</p>');
    }
    return false;
});
$('#resetForm').click(function(){
    $('#Answer_type').val('0');
    $('#selectForm').attr('disabled',false);
    $('#FormSelected').remove();
    if ($('#selection').text().length == 0) {
        $('#selection').html('<p id=\"selection\">Pas de sélection.</p>');
    }
    return false;
});
$('#resetDate').click(function(){
    $('#Answer_last_updated') = '';
    $('#selectDate').attr('disabled',false);
    $('#DateSelected').remove();
    if ($('#selection').text().length == 0) {
        $('#selection').html('<p id=\"selection\">Pas de sélection.</p>');
    }
    return false;
});
");
?>
<div style="margin-left:20px;">
    <div class="myBreadcrumb">
        <div class="active"><?php echo Yii::t('common', 'queryAnonymous') ?></div>
        <div><?php echo Yii::t('common', 'queryFormulation') ?></div>
        <div><?php echo Yii::t('common', 'resultQuery') ?></div>
    </div>
</div>

<div class="search-form">
    <div class="wide form">
        <p>*<?php echo Yii::t('common', 'search1') ?></p>
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'light_search-form',
            'action' => Yii::app()->createUrl('rechercheFiche/admin2'),
            'method' => 'post',
        ));
        ?>

        <div style="border:1px solid black;">

            <h4 style="margin-left:10px;"><u><b><?php echo Yii::t('common', 'queryAnonymous') . " " . Yii::t('common', 'to') ?></b></u></h4>

            <div class="row">
                <div class="col-lg-12">
                    <?php echo CHtml::label(Yii::t('common', 'individualSelection'), 'Answer_id_patient', array('style' => 'width:250px; padding-top:30px;')); ?>
                    <?php echo $form->dropDownList($model, 'id_patient', Answer::model()->getIdPatientFiches(), array("multiple" => "multiple", "onclick" => "restrictQuery()")); ?>
                    <?php echo CHtml::submitButton(Yii::t('button', 'select'), array('id' => 'selectCas', 'class' => 'btn btn-success')); ?>
                    <?php echo CHtml::resetButton(Yii::t('button', 'reset'), array('id' => 'resetCas', 'class' => 'btn btn-danger')); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <?php echo CHtml::label(Yii::t('common', 'restrictQuery'), 'Answer_type', array('style' => 'width:250px; padding-top:30px;')); ?>
                    <?php echo $form->dropDownList($model, 'type', Questionnaire::model()->getArrayType(), array("multiple" => "multiple", "onclick" => "restrictQuery()")); ?>
                    <?php echo CHtml::submitButton(Yii::t('button', 'select'), array('id' => 'selectForm', 'class' => 'btn btn-success')); ?>
                    <?php echo CHtml::resetButton(Yii::t('button', 'reset'), array('id' => 'resetForm', 'class' => 'btn btn-danger')); ?>
                </div>
            </div>

            <div class ="row">
                <div class="col-lg-12">
                    <?php echo CHtml::label(Yii::t('common', 'restrictPeriod'), 'Answer_last_updated', array('style' => 'width:250px; padding-top:10px;')); ?>
                    <?php echo $form->textField($model, 'last_updated', array("onfocus" => "datePicker(this.name)")); ?>
                    <?php echo CHtml::submitButton(Yii::t('button', 'select'), array('id' => 'selectDate', 'class' => 'btn btn-success')); ?>
                    <?php echo CHtml::resetButton(Yii::t('button', 'reset'), array('id' => 'resetDate', 'class' => 'btn btn-danger')); ?>
                </div>
            </div>

            <p style="margin-left:10px; color:red;"><?php echo Yii::t('common', 'notRestrict'); ?></p>

        </div>

        <div class="well">
            <p id="selection">Pas de sélection.</p>
        </div>

        <div class="row buttons">
            <div class="col-lg-7 col-lg-offset-7">
                <?php echo CHtml::submitButton(Yii::t('button', 'next'), array('id' => 'next', 'class' => 'btn btn-primary')); ?>
                <?php echo CHtml::image(Yii::app()->request->baseUrl . '/images/loading.gif', 'loading', array('id' => "loading_next", 'style' => "margin-left: 10px; margin-bottom:10px; display:none;"));?>
            </div>
        </div>

        <?php $this->endWidget(); ?>

    </div><!-- search-form -->
</div>