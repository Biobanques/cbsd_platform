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

            <div class="row buttons">
                <?php echo CHtml::submitButton(Yii::t('common', 'search'), array('name' => 'rechercher', 'class' => 'btn btn-default', 'style' => 'margin-top: 8px; padding-bottom: 23px;')); ?>
                <?php echo CHtml::resetButton(Yii::t('common', 'reset'), array('id' => 'reset', 'class' => 'btn btn-default', 'style' => 'margin-top: 8px; padding-bottom: 23px;')); ?>
                <?php echo CHtml::link(Yii::t('common', 'exportCSV'), array('rechercheFiche/exportCsv'), array('class' => 'btn btn-default')); ?>
            </div>

            <?php $this->endWidget(); ?>

</div><!-- search-form -->