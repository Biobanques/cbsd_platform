<?php
$addRouteQuery = Yii::app()->createAbsoluteUrl('answer/writeQueries');
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});

$('.search-form form').submit(function(){
    $.ajax({
        url:'$addRouteQuery',
        type:'POST',
        data:$('#light_search-form').serialize(),
        success:function(result){
            $('#queries').show();
            $('#queries').html('');
            $('#queries').append(result);
            $('#showResultQuery').show();
            $('.search-form').hide();
        }
    });
    $.fn.yiiGridView.update('searchFiche-grid', {
        data: $(this).serialize()
    });
    return false;
});
$('#selectCas').click(function(){
    if ($('#Answer_id_patient :selected').length > 0) {
        $('#selectCas').attr('disabled',true);
        if (document.getElementById('selection').innerText == 'Pas de sélection.') {
            $('#selection').html('<p id=\"CasSelected\">- Cas sélectionnés</p><br>');
        } else {
            $('#selection').append('<p id=\"CasSelected\">- Cas sélectionnés</p><br>');
        }
    }
    return false;
});
$('#selectForm').click(function(){
    if ($('#Answer_type :selected').length > 0) {
        $('#selectForm').attr('disabled',true);
        if (document.getElementById('selection').innerText == 'Pas de sélection.') {
            $('#selection').html('<p id=\"FormSelected\">- Formulaires sélectionnés</p><br>');
        } else {
            $('#selection').append('<p id=\"FormSelected\">- Formulaires sélectionnés</p><br>');
        }
    }
    return false;
});
$('#selectDate').click(function(){
    if ($('#Answer_last_updated').val().length > 0) {
        $('#selectDate').attr('disabled',true);
        if (document.getElementById('selection').innerText == 'Pas de sélection.') {
            $('#selection').html('<p id=\"DateSelected\">- Période sélectionnée</p><br>');
        } else {
            $('#selection').append('<p id=\"DateSelected\">- Période sélectionnée</p><br>');
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
        <div class="active">Restreindre la requête</div>
        <div>Formuler la requête</div>
        <div>Résultat de la requête</div>
    </div>
</div>

<div class="search-form">
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
                    <?php echo CHtml::label(Yii::t('common', 'individualSelection'), 'Answer_id_patient', array('style' => 'width:250px; padding-top:30px;')); ?>
                    <?php echo $form->dropDownList($model, 'id_patient', Answer::model()->getIdPatientFiches(), array("multiple" => "multiple", "onclick" => "restrictQuery()")); ?>
                    <?php echo CHtml::submitButton('Sélectionner', array('id' => 'selectCas', 'class' => 'btn btn-success')); ?>
                    <?php echo CHtml::resetButton('Réinitialiser', array('id' => 'resetCas', 'class' => 'btn btn-danger')); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <?php echo CHtml::label(Yii::t('common', 'restrictQuery'), 'Answer_type', array('style' => 'width:250px; padding-top:30px;')); ?>
                    <?php echo $form->dropDownList($model, 'type', Questionnaire::model()->getArrayType(), array("multiple" => "multiple", "onclick" => "restrictQuery()")); ?>
                    <?php echo CHtml::submitButton('Sélectionner', array('id' => 'selectForm', 'class' => 'btn btn-success')); ?>
                    <?php echo CHtml::resetButton('Réinitialiser', array('id' => 'resetForm', 'class' => 'btn btn-danger')); ?>
                </div>
            </div>

            <div class ="row">
                <div class="col-lg-12">
                    <?php echo CHtml::label(Yii::t('common', 'restrictPeriod'), 'Answer_last_updated', array('style' => 'width:250px; padding-top:10px;')); ?>
                    <?php echo $form->textField($model, 'last_updated', array("onfocus" => "datePicker(this.name)")); ?>
                    <?php echo CHtml::submitButton('Sélectionner', array('id' => 'selectDate', 'class' => 'btn btn-success')); ?>
                    <?php echo CHtml::resetButton('Réinitialiser', array('id' => 'resetDate', 'class' => 'btn btn-danger')); ?>
                </div>
            </div>

            <p style="margin-left:10px;"><?php echo Yii::t('common', 'notRestrict'); ?></p>

        </div>

        <div class="well">
            <p id="selection">Pas de sélection.</p>
        </div>

        <div class="row buttons">
            <div class="col-lg-7 col-lg-offset-7">
                <?php echo CHtml::submitButton('Suivant', array('id' => 'next', 'class' => 'btn btn-primary')); ?>
            </div>
        </div>

        <?php $this->endWidget(); ?>

    </div><!-- search-form -->
</div>