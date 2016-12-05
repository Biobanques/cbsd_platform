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
            $('#queries').html('');
            $('#queries').append(result);
            }
         });
    $.fn.yiiGridView.update('searchFiche-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
?>

<h1> Voir les fiches associées à ces patients </h1>
<p>*Vous pouvez consulter toutes les fiches associées à ces patients et ajouter de nouvelles conditions (les ID de la requête précédente sont stockés dans une variable).</p>

<?php
$this->widget('application.widgets.menu.CMenuBarLineWidget', array('links' => array(), 'controllerName' => 'rechercheFiche', 'searchable' => true));
?>

<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search_filter', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->
<div id="queries" style="background-color:#80CCFF;"></div>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'post',
        ));

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'searchFiche-grid',
    'type' => 'striped bordered condensed',
    'dataProvider' => $model->searchFilter(),
    'columns' => array(
        array('header' => $model->attributeLabels()["id_patient"], 'name' => 'id_patient'),
        array('header' => $model->attributeLabels()["type"], 'name' => 'type'),
        array('header' => $model->attributeLabels()["name"], 'name' => 'name'),
        array('header' => $model->attributeLabels()["user"], 'name' => 'user', 'value' => '$data->getUserRecorderName()'),
        array('header' => $model->attributeLabels()["last_updated"], 'name' => 'last_updated', 'value' => '$data->getLastUpdated()'),
        array('header' => $model->attributeLabels()["examDate"], 'name' => 'examDate', 'value' => 'date("d/m/Y", strtotime($data->getAnswerByQuestionId("examdate")))'),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
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

<?php $this->endWidget(); ?>

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
    $('input[name="' + clicked + '"]').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
    });
}
</script>