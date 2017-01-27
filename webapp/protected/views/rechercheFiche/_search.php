<?php
$addRoute = Yii::app()->createAbsoluteUrl('answer/addSearchFilter');

Yii::app()->clientScript->registerScript('searchView', "
$('#addFilterButton').click(function(){
    $.ajax({
        url:'$addRoute',
        type:'POST',
        data:$('#question').serialize(),
        success:function(result){
            $('#dynamicFilters').append(result);
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

$('#reset').click(function(){
    $('.col-lg-12').remove();
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
    <div class="row">
        <div class="col-lg-6">
            <?php echo CHtml::label('Sélection individuelle', 'Answer_id_patient'); ?>
            <?php echo $form->dropDownList($model, 'id_patient', Answer::model()->getIdPatientFiches(), array("multiple" => "multiple")); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <?php echo CHtml::label('Restreindre la requête à un formulaire', 'Answer_type'); ?>
            <?php echo $form->dropDownList($model, 'type', Questionnaire::model()->getArrayType(), array("multiple" => "multiple")); ?>
        </div>
    </div>

    <div class ="row">
        <div class="col-lg-6">
            <?php echo CHtml::label('Restreindre la requête à une période', 'Answer_last_updated'); ?>
            <?php echo $form->textField($model, 'last_updated', array("onfocus" => "datePicker(this.name)")); ?>
        </div>
    </div>
    <fieldset style="border:2">
        <legend>Formulation de la requête:</legend>
        <div class="row">
            <div class="col-lg-6">
                <?php echo CHtml::label(Yii::t('common', 'addQuestion'), 'question'); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                    'name' => 'question',
                    'source' => array_map(function($key, $value) {
                                return array('label' => $value, 'value' => $key);
                            }, array_keys(Answer::model()->getAllQuestions()), Answer::model()->getAllQuestions())
                        ));
                        ?>
                        <?php
                        echo CHtml::button(Yii::t('common', 'add'), array('id' => 'addFilterButton', 'class' => 'btn btn-default', 'style' => 'padding-bottom: 23px;'));
                        ?>

                    </div>
                </div>

                <div id="dynamicFilters"></div>
            </fieldset>

            <div class="row buttons">
                <?php echo CHtml::submitButton(Yii::t('common', 'search'), array('name' => 'rechercher', 'class' => 'btn btn-default', 'style' => 'margin-top: 8px; padding-bottom: 23px;')); ?>
                <?php echo CHtml::resetButton(Yii::t('common', 'deleteQuery'), array('id' => 'reset', 'class' => 'btn btn-default', 'style' => 'margin-top: 8px; padding-bottom: 23px;')); ?>

            </div>

            <?php $this->endWidget(); ?>

</div><!-- search-form -->