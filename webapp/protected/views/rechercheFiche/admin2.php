<?php
$addRoute = Yii::app()->createAbsoluteUrl('answer/addSearchFilter');
$addRouteQuery = Yii::app()->createAbsoluteUrl('answer/writeQueries');
$add = Yii::app()->createAbsoluteUrl('answer/test');

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
        $('#queries').html('');
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

$('#next').click(function(){
    $('#loading_next').show();
    $.ajax({
        url:'$add',
        type:'POST',
        data:$('#search_fiche-form').serialize(),
        success:function(result){
            $('#renderFiche').html(result);
            var hash = window.location.hash.substr(1);
            location.hash = hash;
            $('#loading_next').hide();
        }
    });

     return false;
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
    </div>
</div>

<div id="queries" style="background-color:#E5F1F4;box-shadow: 5px 5px 5px #888888;padding:1px;"></div>
<br>
<div class="row buttons">
    <div class="col-lg-7 col-lg-offset-7">
        <?php echo CHtml::submitButton(Yii::t('button', 'search'), array('id' => 'search', 'class' => 'btn btn-primary')); ?>
        <?php echo CHtml::resetButton(Yii::t('button', 'deleteQuery'), array('id' => 'reset', 'class' => 'btn btn-danger', 'onclick' => 'location.reload();')); ?>
    </div>
</div>

<?php $this->endWidget(); ?>

<?php if (isset($_SESSION['fiche']) && $_SESSION['fiche'] != null) { ?>
    <div class="search-form">
        <div class="wide form">
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'search_fiche-form',
                'action' => Yii::app()->createUrl($this->route),
                'method' => 'post',
            ));
            ?>
            <div id="renderFiche">
                <?php echo $_SESSION['fiche']->renderHTML(Yii::app()->language); ?>
            </div>
        </div><!-- search-form -->
        <?php echo CHtml::hiddenField('hash', '', array('id' => 'hash')); ?>
        <div class="row buttons">
            <div class="col-lg-7 col-lg-offset-7">
                <?php echo CHtml::submitButton(Yii::t('button', 'next'), array('id' => 'next', 'class' => 'btn btn-primary')); ?>
                <?php echo CHtml::image(Yii::app()->request->baseUrl . '/images/loading.gif', 'loading', array('id' => "loading_next", 'style' => "margin-left: 10px; margin-bottom:10px; display:none;"));?>
            </div>
        </div>

        <?php $this->endWidget(); ?>


    </div>
<?php } ?>

<div id="test"></div>