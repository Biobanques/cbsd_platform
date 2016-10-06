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
    <p>*Lorsque vous ajoutez une question, vous pouvez ajouter plusieurs valeurs dans les champs de type "input" en les séparant par une virgule (opérateur "OU").</p>
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
    ));
    ?>
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
        <?php echo CHtml::resetButton('Réinitialiser', array('class' => 'btn btn-default', 'style' => 'margin-top: 8px; padding-bottom: 23px;')); ?>
        <?php echo CHtml::link('Exporter en CSV', array('rechercheFiche/exportCsv'), array('class' => 'btn btn-default')); ?>
        <?php echo CHtml::link('Voir les fiches associées à ces patients', array('rechercheFiche/resultSearch'), array('class' => 'btn btn-default')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->