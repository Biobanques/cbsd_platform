<?php
$addRoute = Yii::app()->createAbsoluteUrl('answer/addSearchFilter');

Yii::app()->clientScript->registerScript('searchView', "
$('#addFilterButton').click(function(){
    $('#addFilterButton').hide();
    $('#loading').show();
    $.ajax({
        url:'$addRoute',
        type:'POST',
        data:$('#question').serialize(),
        success:function(result){
            $('#dynamicFilters').append(result);
            $('#addFilterButton').show();
            document.getElementById('addFilterButton').disabled = true;
            $('#loading').hide();
            $('#question').val('');
            var n = $('.deleteQuestion').length;
            if (n == 1) {
                $('.condition').hide();
            }
            }
         });

     return false;
});

$('#dynamicFilters').on('click','.deleteQuestion',function(event){
    event.target.closest('.col-lg-12').remove();
    var n = $('.deleteQuestion').length;
    if (n == 1) {
        $('.condition').hide();
    }
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
        'method' => 'get',
    ));
    ?>

    <div style="border:1px solid black;">
        <h4 style="margin-left:10px;"><u><b><?php echo Yii::t('common', 'queryAnonymous') ?></b></u></h4>
        <div class="row">
            <div class="col-lg-12">
                <?php echo CHtml::label(Yii::t('common', 'indivualSelection'), 'Answer_id_patient', array('style' => 'width:200px')); ?>
                <?php echo $form->dropDownList($model, 'id_patient', Answer::model()->getIdPatientFiches(), array("multiple" => "multiple")); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <?php echo CHtml::label(Yii::t('common', 'restrictQuery'), 'Answer_type', array('style' => 'width:200px')); ?>
                <?php echo $form->dropDownList($model, 'type', Questionnaire::model()->getArrayType(), array("multiple" => "multiple")); ?>
            </div>
        </div>

        <div class ="row">
            <div class="col-lg-12">
                <?php echo CHtml::label(Yii::t('common', 'restrictPeriod'), 'Answer_last_updated', array('style' => 'width:200px')); ?>
                <?php echo $form->textField($model, 'last_updated', array("onfocus" => "datePicker(this.name)")); ?>
            </div>
        </div>
    </div>

    <hr/>

    <div style="border:1px solid black;">
        <h4 style="margin-left:10px;"><u><b><?php echo Yii::t('common', 'queryFormulation') ?></b></u></h4>
        <p>&nbsp;&nbsp;*Taper une lettre ou la touche "espace" pour afficher toutes les variables</p>
        <div class="row">
            <div class="col-lg-12">
                <?php echo CHtml::label(Yii::t('common', 'addQuestion'), 'question'); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                    'name' => 'question',
                    'source' => array_map(function($key, $value) {
                                return array('label' => $value, 'value' => $key);
                            }, array_keys(Answer::model()->getAllQuestions()), Answer::model()->getAllQuestions()),
                    'htmlOptions'=>array(
                        'onkeyup'=>'document.getElementById("addFilterButton").disabled = false;'
                        )));
                        echo CHtml::button(Yii::t('common', 'logicOperator'), array('id' => 'addFilterButton', 'class' => 'btn btn-default', 'style' => 'margin-left:10px; padding-bottom:23px; font-weight:bold;', 'disabled' => 'disabled'));
                        echo CHtml::image(Yii::app()->request->baseUrl . '/images/loading.gif', 'loading', array('id' => "loading", 'style' => "margin-left: 10px; margin-bottom:10px; display:none;"));
                        ?>
                    </div>
                </div>

                <div id="dynamicFilters" style="margin-left:50px;"></div>

                <div class="row">
                    <div class="col-lg-2 col-lg-offset-7">
                        <?php echo CHtml::submitButton(Yii::t('common', 'search'), array('name' => 'rechercher', 'class' => 'btn btn-default', 'style' => 'margin-top: 8px; padding-bottom: 23px;')); ?>
                    </div>
                    <div class="col-lg-2">
                        <?php echo CHtml::resetButton(Yii::t('common', 'deleteQuery'), array('id' => 'reset', 'class' => 'btn btn-default', 'style' => 'margin-top: 8px; padding-bottom: 23px;')); ?>
                    </div>
                </div>
            </div>
            <?php $this->endWidget(); ?>

</div><!-- search-form -->