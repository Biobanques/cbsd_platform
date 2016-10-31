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
    <p>*Pour les champs à choix multiples, vous pouvez sélectionner plusieurs valeurs avec la touche CTRL du clavier.</p>
    <p>*Lorsque vous ajoutez une question, vous pouvez ajouter plusieurs valeurs dans les champs de type "input" en les séparant par une virgule (opérateur "OU").</p>
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'light_search-form',
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
    ));
    ?>
    <div class="row">
        <div class="col-lg-6">
            <?php echo $form->label($model, 'id_patient'); ?>
            <?php echo $form->dropDownList($model, 'id_patient', Answer::model()->getIdPatientFiches(), array("multiple" => "multiple")); ?>
        </div>


        <div class="col-lg-6">
            <?php echo $form->label($model, 'user'); ?>
            <?php echo $form->dropDownList($model, 'user', Answer::model()->getNamesUsers(), array("multiple" => "multiple")); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <?php echo $form->label($model, 'type'); ?>
            <?php echo $form->dropDownList($model, 'type', Questionnaire::model()->getArrayType(), array("multiple" => "multiple")); ?>
        </div>

        <div class="col-lg-6">
            <?php echo $form->label($model, 'name'); ?>
            <?php echo $form->dropDownList($model, 'name', Answer::model()->getNomsFiches(), array("multiple" => "multiple")); ?>
        </div>
    </div>

    <div class ="row">
        <div class="col-lg-6">
            <?php echo $form->label($model, 'last_updated'); ?>
            <?php echo $form->textField($model, 'last_updated', array("onfocus"=>"datePicker(this.name)")); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <?php echo CHtml::label('Ajouter une question', 'question'); ?>
            <?php
            $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                'name' => 'question',
                'source' => array_map(function($key, $value) {
                            return array('label' => $value, 'value' => $key);
                        }, array_keys(Answer::model()->getAllQuestions()), Answer::model()->getAllQuestions())
                    ));
                    ?>
                    <?php
                    echo CHtml::button('ajouter', array('id' => 'addFilterButton', 'class' => 'btn btn-default', 'style' => 'padding-bottom: 23px;'));
                    ?>

                </div>
            </div>

            <div id="dynamicFilters"></div>

            <div class="row buttons">
                <?php echo CHtml::submitButton('Rechercher', array('name' => 'rechercher', 'class' => 'btn btn-default', 'style' => 'margin-top: 8px; padding-bottom: 23px;')); ?>
                <?php echo CHtml::resetButton('Réinitialiser', array('id' => 'reset', 'class' => 'btn btn-default', 'style' => 'margin-top: 8px; padding-bottom: 23px;')); ?>
                <?php echo CHtml::link('Exporter en CSV', array('rechercheFiche/exportCsv'), array('class' => 'btn btn-default')); ?>
            </div>

            <?php $this->endWidget(); ?>

</div><!-- search-form -->