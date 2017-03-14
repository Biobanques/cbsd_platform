<?php
$addRouteQuery = Yii::app()->createAbsoluteUrl('answer/writeQueries');
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});

$('.search-form form').submit(function(){
    $.ajax({
        url:'$addRouteQuery',
        type:'POST',
        data:$('#light_search-form').serialize(),
        success:function(result){
            $('#queries').show();
            $('#queries').html('');
            $('#queries').append(result);
            $('#showResultQuery').show();
            $('.search-form').hide();
        }
    });
    $.fn.yiiGridView.update('searchFiche-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
?>

<?php
if (Yii::app()->user->getActiveProfil() == "administrateur de projet") {
    ?> <h1><?php echo "Gestion de projet"; ?></h1>
<?php } else { ?>
    <h1><?php echo Yii::t('common', 'availablePatientForms') ?></h1>
<?php } ?>
<?php

    $this->widget('application.widgets.menu.CMenuBarLineWidget', array('links' => array(), 'controllerName' => 'rechercheFiche', 'searchable' => true));
?>
<div class="search-form">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->
<div id="queries" style="background-color:#E5F1F4;box-shadow: 5px 5px 5px #888888;padding:1px;display:none;"></div>

<hr />

<div id="showResultQuery" style="display:none;">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl('rechercheFiche/resultsearch'),
        'method' => 'post',
    ));
    ?>

    <div class="row">
        <div class="col-lg-5">
            <?php echo CHtml::link(Yii::t('common', 'exportCSV'), array('rechercheFiche/exportCsv'), array('class' => 'btn btn-primary')); ?>
        </div>
        <div class="col-lg-5">
            <?php echo CHtml::submitButton(Yii::t('common', 'patientFormsAssociated'), array('name' => 'rechercher', 'class' => 'btn btn-primary')); ?>
        </div>
    </div>

    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'searchFiche-grid',
        'dataProvider' => $model->search(),
        'columns' => array(
            array('id' => 'Answer_id_patient', 'value' => '$data->id_patient', 'class' => 'CCheckBoxColumn', 'selectableRows' => 2),
            array('header' => $model->attributeLabels()["id_patient"], 'name' => 'id_patient'),
            array('header' => $model->attributeLabels()["type"], 'name' => 'type'),
            array('header' => $model->attributeLabels()["name"], 'name' => 'name'),
            array('header' => $model->attributeLabels()["user"], 'name' => 'user', 'value' => '$data->getUserRecorderName()'),
            array('header' => $model->attributeLabels()["last_updated"], 'name' => 'last_updated', 'value' => '$data->getLastUpdated()'),
            array('header' => $model->attributeLabels()["examDate"], 'name' => 'examDate', 'value' => '$data->getAnswerByQuestionId("examdate")'),
            array(
                'class' => 'CButtonColumn',
                'template' => '{view}{update}{delete}',
                'buttons' => array(
                    'view' => array(
                        'click' => 'function(){window.open(this.href,"_blank","left=100,top=100,width=1200,height=650,toolbar=yes, scrollbars=yes, resizable=yes, location=no");return false;}'
                    ),
                    'update' => array(
                        'visible' => 'Yii::app()->user->isMaster() && Yii::app()->user->isAuthorizedUpdate(Yii::app()->user->getState(\'activeProfil\'), $data->type)'
                    ),
                    'delete' => array(
                        'visible' => 'Yii::app()->user->isMaster() && Yii::app()->user->isAuthorizedDelete(Yii::app()->user->getState(\'activeProfil\'), $data->type)'
                    )
                ),
            ),
        ),
    ));
    ?>
    <div class="row">
        <div class="col-lg-5">
            <?php echo CHtml::link(Yii::t('common', 'exportCSV'), array('rechercheFiche/exportCsv'), array('class' => 'btn btn-primary')); ?>
        </div>
        <div class="col-lg-5">
            <?php echo CHtml::submitButton(Yii::t('common', 'patientFormsAssociated'), array('name' => 'rechercher', 'class' => 'btn btn-primary')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div>