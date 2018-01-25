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

$('#resetFilterButton').click(function(){
    document.getElementById('addFilterButton').disabled = false;
    $('#question').prop('readonly', false);
    $('#question').val('');
    $('#resetFilterButton').hide();
    document.getElementById('addFilterButton').disabled = true;
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
            $('#resetFilterButton').show();
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
    $('#question').prop('readonly', false);
    $('#question').val('');
    return false;
});

$('#search_fiche-form').on('click','.question-input',function(event){
    '#'+$('#hash').val(event.target.id);
    location.hash = event.target.id;
});
");
?>

<style>
    .ui-icon {top: 0 !important;}
    .ui-widget{font-family: Arial,Helvetica,sans-serif; font-size: 1em;}

</style>

<div style="margin-left:20px;">
    <div class="myBreadcrumb">
        <div class="active"><?php echo CHtml::link(Yii::t('common', 'queryAnonymous'), array('rechercheFiche/admin'), array('style' => 'color:black')); ?></div>
        <div class="active"><?php echo Yii::t('common', 'queryFormulation') ?></div>
        <div><?php echo Yii::t('common', 'resultQuery') ?></div>
    </div>
</div>
<div class="wide form">

    <div style="border:1px solid black;">

        <h4 style="margin-left:10px;"><u><b><?php echo "Requête portant dans le formulaire sur la variable :" ?></b></u></h4>

        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'light_search-form',
            'action' => Yii::app()->createUrl($this->route),
            'method' => 'post',
        ));
        ?>

        <p>&nbsp;&nbsp;<?php echo Yii::t('common', 'writeQuestion'); ?></p>

        <?php if (isset($_POST['searchAll'])) { ?>
            <div class="row">
                <div class="col-lg-12">
                    <?php echo CHtml::label(Yii::t('common', 'restrictQuery'), 'Answer_type'); ?>
                    <?php echo $form->dropDownList($model, 'type', Questionnaire::model()->getNomsFiches()); ?>
                </div>
            </div>

        <?php } ?>

        <div class="row">
            <div class="col-lg-12">
                <?php echo CHtml::label(Yii::t('common', 'addQuestion'), 'question'); ?>
                <?php
                $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                    'name' => 'question',
                    'source' => array_map(function($key, $value) {
                                return array('label' => $value, 'value' => $key);
                            }, array_keys(Answer::model()->getAllQuestionsByTypeForm($_POST['Answer']['type'])), Answer::model()->getAllQuestionsByTypeForm($_POST['Answer']['type'])),
                    'options' => array(
                        'showAnim' => 'fold',
                        'select' => 'js:function(event, ui){ $("#question").attr("readonly", true); '
                        . 'document.getElementById("addFilterButton").disabled = false;}')
                ));
                echo CHtml::button(Yii::t('button', 'logicOperator'), array('id' => 'addFilterButton', 'class' => 'btn btn-info btn-sm', 'style' => 'margin-left:10px; margin-bottom:10px; font-size: 1.0em; font-weight:bold;', 'disabled' => 'disabled'));
                echo CHtml::image(Yii::app()->request->baseUrl . '/images/loading.gif', 'loading', array('id' => "loading", 'style' => "margin-left: 10px; margin-bottom:10px; display:none;"));
                echo CHtml::button(Yii::t('button', 'addVariable'), array('id' => 'resetFilterButton', 'class' => 'btn btn-success btn-sm', 'style' => 'margin-left:10px; margin-bottom:10px; font-size: 1.0em; font-weight:bold; display:none;'));
                ?>
            </div>
            <div id="dynamicFilters" style="margin-left:50px;display:none;"></div>
        </div>
        <br><br><br><br>
        <div class="row buttons">
            <div class="col-lg-7 col-lg-offset-7">
                <?php echo CHtml::submitButton(Yii::t('button', 'runQuery'), array('id' => 'search', 'name' => 'runQuery', 'class' => 'btn btn-primary', 'onclick' => 'submit()')); ?>
                <?php echo CHtml::resetButton(Yii::t('button', 'deleteQuery'), array('id' => 'reset', 'class' => 'btn btn-danger', 'onclick' => 'location.reload();')); ?>
            </div>
        </div>

        <?php $this->endWidget(); ?>
    </div>
</div>

<?php if (isset($html) && $html != null) { ?>
    <div id="queries">
        <h4><u><?php echo Yii::t('common', 'history') ?></u></h4>
        <?php echo "<ul>" . $html->html . "</ul>"; ?>
    </div>
    <?php
}