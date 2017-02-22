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
if (Yii::app()->controller->action == "admin") {
    $this->widget('application.widgets.menu.CMenuBarLineWidget', array('links' => array(), 'controllerName' => 'rechercheFiche', 'searchable' => true));
}
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
            <?php echo CHtml::button(Yii::t('common', 'exportCSV'), array('class' => 'btn btn-primary btn-md', 'data-toggle' => "modal", 'data-target' => "#myModal")); ?>
        </div>
        <div class="col-lg-5">
            <?php echo CHtml::submitButton(Yii::t('common', 'patientFormsAssociated'), array('name' => 'rechercher', 'class' => 'btn btn-default')); ?>
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
                'template' => '{view}',
                'buttons' => array(
                    'view' => array(
                        'click' => 'function(){window.open(this.href,"_blank","left=100,top=100,width=1200,height=650,toolbar=yes, scrollbars=yes, resizable=yes, location=no");return false;}'
                    ),
                ),
            ),
        ),
    ));
    ?>
    <div class="row">
        <div class="col-lg-5">
            <?php echo CHtml::button(Yii::t('common', 'exportCSV'), array('class' => 'btn btn-primary btn-md', 'data-toggle' => "modal", 'data-target' => "#myModal")); ?>
        </div>
        <div class="col-lg-5">
            <?php echo CHtml::submitButton(Yii::t('common', 'patientFormsAssociated'), array('name' => 'rechercher', 'class' => 'btn btn-default')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

    <div id="myModal"  class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h1 class="modal-title"><?php echo Yii::t('common', 'exportFields'); ?></h1>
                </div>
                <div class="modal-body">
                    <div class="prefs-form" >
                        <?php
                        $model = new Answer('search');
                        $model->unsetAttributes();
                        if (isset($_GET['Answer'])) {
                            $model->attributes = $_GET['Answer'];
                        }
                        if (isset($_SESSION['criteria']) && $_SESSION['criteria'] != null && $_SESSION['criteria'] instanceof EMongoCriteria) {
                            $criteria = $_SESSION['criteria'];
                        } else {
                            $criteria = new EMongoCriteria;
                        }
                        // trier par id_patient et type de fiche dans l'ordre croissant
                        $criteria->sort('id_patient', EMongoCriteria::SORT_ASC);
                        $criteria->sort('type', EMongoCriteria::SORT_ASC);
                        $models = Answer::model()->findAll($criteria);
                        $_SESSION['models'] = $models;
                        if (count($models) < 1) {
                            Yii::app()->user->setFlash("erreur", Yii::t('common', 'emptyPatientFormExport'));
                            $this->redirect(array("rechercheFiche/admin"));
                        }
                        ?>
                        <?php
                        $form = $this->beginWidget('CActiveForm', array(
                            'action' => Yii::app()->createUrl($this->route),
                        ));
                        ?>
                        <label><input type="checkbox" name="select-all" id="select-all" />&nbsp;&nbsp;&nbsp;<?php echo Yii::t('common', 'selectAll'); ?></label><br>
                        <div class="checkboxgroup"> 
                            <?php
                            $fiches = Answer::model()->getNomsFichesByFilter($models);
                            echo CHtml::checkBoxList('filter', 'addFilter', Answer::model()->attributeExportedLabels(), array(
                                'labelOptions' => array('style' => 'display:inline'),
                                'separator' => '',
                                'template' => '<div>{input}&nbsp;{label}</div><br>'
                            ));
                            foreach ($fiches as $key => $value) {
                                ?><table><?php
                                    echo "<h3>Fiche " . $value . "</h3>";
                                    echo CHtml::checkBoxList('filter', 'addFilter', Answer::model()->getAllQuestionsByFilterName($models, $value), array(
                                        'labelOptions' => array('style' => 'display:inline'),
                                        'separator' => '',
                                        'template' => '<tr><td>{input}&nbsp;{label}</td></tr>'
                                    ));
                                    ?></table><?php
                            }
                            ?>
                        </div><br>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group btn-group-justified" role="group" aria-label="group button">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"  role="button">Close</button>
                        </div>
                        <div class="btn-group btn-delete hidden" role="group">
                            <button type="button" id="delImage" class="btn btn-default btn-hover-red" data-dismiss="modal"  role="button">Delete</button>
                        </div>
                        <div class="btn-group" role="group">
                            <?php echo CHtml::submitButton('Exporter', array('name' => 'exporter', 'class' => 'btn btn-primary')); ?>
                        </div>
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

</div>

<script>
    function datePicker(clicked) {
        $('input[name="' + clicked + '"]').daterangepicker({
            "applyClass": "btn-primary",
            "showDropdowns": true,
            locale: {
                format: "DD/MM/YYYY",
                applyLabel: 'Valider',
                cancelLabel: 'Effacer'
            }
        });
        $('#restrictSearch').show();
        $('#restrictReset').show();
        $('input[name="' + clicked + '"]').on('apply.daterangepicker', function (ev, picker) {
            $('#restrictSearch').show();
            $('#restrictReset').show();
        });
        $('input[name="' + clicked + '"]').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
            if ($('.col-lg-12 :selected').text() == "") {
                $('#restrictSearch').hide();
                $('#restrictReset').hide();
            }
        });
    }
</script>