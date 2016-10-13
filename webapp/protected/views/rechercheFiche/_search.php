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
    <p>*Pour les champs à choix multiples, vous pouvez sélectionner plusieurs valeurs avec la touche CTRL du clavier.</p>
    <p>*Lorsque vous ajoutez une question, vous pouvez ajouter plusieurs valeurs dans les champs de type "input" en les séparant par une virgule (opérateur "OU").</p>
    <?php
    $form = $this->beginWidget('CActiveForm', array(
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
        <h5 aligen="center">Date de saisie</h5>
        <div class="col-lg-6">
            <?php
            echo CHtml::label('Du', 'last_updated_from');
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => 'Answer[last_updated_from]',
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
            <?php echo CHtml::label('Au', 'last_updated'); ?>
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

    <div class="row">
        <div class="col-lg-12">
            <?php echo CHtml::label('Ajouter une question', 'question'); ?>
            <?php
            $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                'name' => 'question',
                'source' => array_map(function($key, $value) {
                            return array('label' => $value, 'value' => $key);
                        }, array_keys(Answer::model()->getAllQuestions()), Answer::model()->getAllQuestions()),
                        // additional javascript options for the autocomplete plugin
                        'options' => array(
                            'minLength' => '2',
                        )
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
                <?php echo CHtml::resetButton('Réinitialiser', array('class' => 'btn btn-default', 'style' => 'margin-top: 8px; padding-bottom: 23px;')); ?>
                <?php echo CHtml::link('Exporter en CSV', array('rechercheFiche/exportCsv'), array('class' => 'btn btn-default')); ?>
            </div>

            <?php $this->endWidget(); ?>

</div><!-- search-form -->