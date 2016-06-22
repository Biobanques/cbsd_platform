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
            }
         });

     return false;
});


$('#dynamicFilters').on('click','.deleteQuestion',function(event){

//alert(event.target.closest('.col-lg-6').className);
event.target.closest('.col-lg-6').remove();
return false;
});

");
?>


<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
    ));
    ?>
    <div class="row">
        <div class="col-lg-6">
            <?php echo $form->label($model, 'id_patient'); ?>
            <?php echo $form->dropDownList($model, 'id_patient', Answer::model()->getIdPatientFiches(), array('prompt' => '----', "multiple" => "multiple")); ?>
        </div>


        <div class="col-lg-6">
            <?php echo $form->label($model, 'user'); ?>
            <?php echo $form->dropDownList($model, 'user', Answer::model()->getNamesUsers(), array('prompt' => '----', "multiple" => "multiple")); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <?php echo $form->label($model, 'type'); ?>
            <?php echo $form->dropDownList($model, 'type', Questionnaire::model()->getArrayType(), array('prompt' => '----', "multiple" => "multiple")); ?>
        </div>

        <div class="col-lg-6">
            <?php echo $form->label($model, 'name'); ?>
            <?php echo $form->dropDownList($model, 'name', Questionnaire::model()->getNomsFiches(), array('prompt' => '----', "multiple" => "multiple")); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <?php
            echo CHtml::label('Date d\'examen', 'Answer[dynamics][examdate]');
            ?>
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => 'Answer[dynamics][examdate]',
                //
                //  additional javascript options for the date picker plugin
                'options' => array(
                    'showAnim' => 'fold',
                ),
                'htmlOptions' => array(
                    'style' => 'height:25px;'
                ),
                'language' => 'fr',
            ));
            ?>
        </div>

        <div class="col-lg-6">
            <?php echo $form->label($model, 'last_updated'); ?>
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => 'Answer[last_updated]',
                // additional javascript options for the date picker plugin
                'options' => array(
                    'showAnim' => 'fold',
                ),
                'htmlOptions' => array(
                    'style' => 'height:25px;'
                ),
                'language' => 'fr',
            ));
            ?>
        </div>
    </div>
    <div id="dynamicFilters"></div>
    <div class="row">
        <div class="col-lg-12">
            <?php echo CHtml::label('Ajouter une question', 'question'); ?>
            <?php echo CHtml::dropDownList('question', 'addQuestion', Answer::model()->getAllQuestions(), array('prompt' => '----')); ?>
            <?php
            echo CHtml::button('ajouter', array('id' => 'addFilterButton', 'class' => 'btn btn-default', 'style' => 'padding-bottom: 23px;'));
            ?>

        </div>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Rechercher', array('name' => 'rechercher', 'class' => 'btn btn-default', 'style' => 'margin-top: 8px; padding-bottom: 23px;')); ?>
        <?php echo CHtml::link('Exporter en CSV', array('rechercheFiche/exportCsv'), array('class' => 'btn btn-default')); ?>
        <?php echo CHtml::link('Exporter en SQL', array('rechercheFiche/exportSql'), array('class' => 'btn btn-default')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->