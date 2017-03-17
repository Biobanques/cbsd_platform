<?php
$addRoute = Yii::app()->createAbsoluteUrl('answer/addSearchReplaceFilter');
$addRouteQuery = Yii::app()->createAbsoluteUrl('answer/writeQueries');

Yii::app()->clientScript->registerScript('searchView', "
$('#addFilterButton').click(function(){
    $('#addFilterButton').hide();
    $('#loading').show();
    $.ajax({
        url:'$addRoute',
        type:'POST',
        data:$('#question').serialize(),
        success:function(result){
            $('#dynamicFilters').show();
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
$('#dynamicFilters').on('click','.validateQuery',function(event){
    $(this).parent().hide();
    $.ajax({
        url:'$addRouteQuery',
        type:'POST',
        data:$('#light_search-form').serialize(),
        success:function(result){
        $('#queries').show();
            $('#queries').html('');
            $('#queries').append(result);
            $('#search').show();
            $('#reset').show();
        }
    })
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
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'light_search-form',
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'post',
    ));
    ?>

    <div style="border:1px solid black;">
        <h4 style="margin-left:10px;"><u><b><?php echo Yii::t('common', 'queryFormulation') ?></b></u></h4>
        <p>&nbsp;&nbsp;*Taper une lettre ou la touche "espace" pour afficher toutes les variables</p>
        <div class="row">
            <div class="col-lg-12">
                <?php echo CHtml::label(Yii::t('common', 'addQuestion'), 'question', array('style' => 'width:200px')); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                    'name' => 'question',
                    'source' => array_map(function($key, $value) {
                                return array('label' => $value, 'value' => $key);
                            }, array_keys(Answer::model()->getAllQuestions()), Answer::model()->getAllQuestions()),
                    'htmlOptions'=>array(
                        'onkeyup'=>'document.getElementById("addFilterButton").disabled = false;'
                        )));
                        echo CHtml::button(Yii::t('common', 'logicOperator'), array('id' => 'addFilterButton', 'class' => 'btn btn-info', 'style' => 'margin-left:10px; font-weight:bold;', 'disabled' => 'disabled'));
                        echo CHtml::image(Yii::app()->request->baseUrl . '/images/loading.gif', 'loading', array('id' => "loading", 'style' => "margin-left: 10px; margin-bottom:10px; display:none;"));
                        ?>
                    </div>
                </div>

                <div id="dynamicFilters" style="margin-left:50px;display:none;"></div>

                <div class="row">
                    <div class="col-lg-2 col-lg-offset-7">
                        <?php echo CHtml::submitButton(Yii::t('common', 'search'), array('id' => 'search', 'class' => 'btn btn-primary')); ?>
                    </div>
                    <div class="col-lg-2">
                        <?php echo CHtml::resetButton(Yii::t('common', 'deleteQuery'), array('id' => 'reset', 'class' => 'btn btn-danger', 'onclick' => 'location.reload();')); ?>
                    </div>
                </div>
            </div>
            <?php $this->endWidget(); ?>

</div><!-- search-form -->