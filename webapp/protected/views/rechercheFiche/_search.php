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
            <?php echo $form->textField($model, 'id_patient'); ?>
        </div>


        <div class="col-lg-6">
            <?php echo $form->label($model, 'user'); ?>
            <?php echo $form->textField($model, 'user'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <?php echo $form->label($model, 'type'); ?>
            <?php // echo $form->textField($model, 'type'); ?>
            <?php echo CHtml::dropDownList('Answer[type]', 'Answer[type]', Answer::model()->getAllTypes(), array('prompt' => '----')); ?>
        </div>

        <div class="col-lg-6">
            <?php echo $form->label($model, 'name'); ?>
            <?php echo $form->textField($model, 'name'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <?php
//            echo $form->label($model, 'last_modified');
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
            echo CHtml::button('ajouter', array('id' => 'addFilterButton'));
//            echo CHtml::ajaxSubmitButton(
//                'Ajouter', Yii::app()->createUrl('site/index'),
//                array(
//                    'type' => 'POST',
//                    //'success' => 'js:function(data){alert(data);}',
//                    'success' => 'js:function(){var e = document.getElementById("question");
//var strUser = e.options[e.selectedIndex].value; alert(strUser);}',
//                    'error'=>'js:function(data){alert("comment NOT Submitted");}',
            //                )
//            );
            ?>

        </div>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Rechercher', array('name' => 'rechercher')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->