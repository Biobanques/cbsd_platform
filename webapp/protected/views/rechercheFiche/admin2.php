<?php
$addRoute = Yii::app()->createAbsoluteUrl('answer/addSearchFilter');
$addRouteQuery = Yii::app()->createAbsoluteUrl('answer/writeQueries');

Yii::app()->clientScript->registerScript('searchView', "
$(document).ready(function() {
    $('.question-input input').prop('readonly', true);
    $('.question-input input').css('background-color', '#dddddd');
});

$('#addFilterButton').click(function(){
    $('#addFilterButton').hide();
    $('#loading').show();
    $.ajax({
        url:'$addRoute',
        type:'POST',
        data:$('#question').serialize(),
        success:function(result){
            if (result == '') {
                $('#question').val('');
            }
            $('#dynamicFilters').show();
            $('#dynamicFilters').append(result);
            $('#addFilterButton').show();
            document.getElementById('addFilterButton').disabled = true;
            $('#loading').hide();
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
        $('#queries').append(result);
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

$('#search_fiche-form').on('click','.question-input',function(event){
    '#'+$('#hash').val(event.target.id);
    location.hash = event.target.id;
});
");
?>
<div style="margin-left:20px;">
    <div class="myBreadcrumb">
        <div class="active"><?php echo Yii::t('common', 'queryAnonymous') ?></div>
        <div class="active"><?php echo Yii::t('common', 'queryFormulation') ?></div>
        <div><?php echo Yii::t('common', 'resultQuery') ?></div>
    </div>
</div>
<div class="wide form">

    <div style="border:1px solid black;">

        <h4 style="margin-left:10px;"><u><b><?php echo Yii::t('common', 'queryFormulation') ?></b></u></h4>

        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'light_search-form',
            'action' => Yii::app()->createUrl($this->route),
            'method' => 'post',
        ));
        ?>

        <p>&nbsp;&nbsp;<?php echo Yii::t('common', 'writeQuestion'); ?></p>

        <div class="row">
            <div class="col-lg-12">
                <?php echo CHtml::label(Yii::t('common', 'addQuestion'), 'question'); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                    'name' => 'question',
                    'source' => array_map(function($key, $value) {
                                return array('label' => $value, 'value' => $key);
                            }, array_keys(Answer::model()->getAllQuestionsByTypeForm($_SESSION['typeForm'])), Answer::model()->getAllQuestionsByTypeForm($_SESSION['typeForm'])),
                    'htmlOptions' => array(
                        'onkeyup' => 'document.getElementById("addFilterButton").disabled = false;'
                )));
                echo CHtml::button(Yii::t('button', 'logicOperator'), array('id' => 'addFilterButton', 'class' => 'btn btn-info', 'style' => 'margin-left:10px; font-weight:bold;', 'disabled' => 'disabled'));
                echo CHtml::image(Yii::app()->request->baseUrl . '/images/loading.gif', 'loading', array('id' => "loading", 'style' => "margin-left: 10px; margin-bottom:10px; display:none;"));
                ?>
            </div>
            <div id="dynamicFilters" style="margin-left:50px;display:none;"></div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?php echo CHtml::label("Available","Available"); ?>
                <?php echo CHtml::dropDownList("Available", 'prvt_available', CommonTools::getAllPrelevements(), array("id" => "multiselect_simple", "class" => "multiselect", "multiple" => "multiple", "style" => "width:60%;")); ?>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-12">
                <?php echo CHtml::label("Not_available","Not available"); ?>
                <?php echo CHtml::dropDownList("NotAvailable", 'prvt_notAvailable', CommonTools::getAllPrelevements(), array("id" => "multiselect_groups", "class" => "multiselect", "multiple" => "multiple", "style" => "width:60%;")); ?>
            </div>
        </div>

        <div class="row buttons">
            <div class="col-lg-7 col-lg-offset-7">
                <?php echo CHtml::submitButton(Yii::t('button', 'search'), array('id' => 'search', 'class' => 'btn btn-primary', 'onclick'=>'submit()')); ?>
                <?php echo CHtml::resetButton(Yii::t('button', 'deleteQuery'), array('id' => 'reset', 'class' => 'btn btn-danger', 'onclick' => 'location.reload();')); ?>
            </div>
        </div>

        <?php $this->endWidget(); ?>
    </div>
</div>
<div><h4><u><?php echo Yii::t('common', 'queriedAnonymous') ?></u></h4><?php echo $html; ?>
    <div id="queries" style="background-color:#E5F1F4;box-shadow: 5px 5px 5px #888888;padding:1px;"><?php
        if (isset($_SESSION['formulateQuery'])) {
            echo $_SESSION['formulateQuery'];
        };
        ?></div>
</div>