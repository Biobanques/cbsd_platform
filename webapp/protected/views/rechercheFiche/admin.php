<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('searchFiche-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
?>

<h1>Liste des fiches disponibles</h1>

<?php
$this->widget('application.widgets.menu.CMenuBarLineWidget', array('links' => array(), 'controllerName' => 'rechercheFiche', 'searchable' => true));
?>

<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'searchFiche-grid',
    'type' => 'striped bordered condensed',
    'dataProvider' => $model->search(),
    'columns' => array(
        //   'id_patient',
        array('header' => $model->attributeLabels()["id_patient"], 'name' => 'id_patient'),
        array('header' => $model->attributeLabels()["id"], 'name' => 'id'),
        array('header' => $model->attributeLabels()["name"], 'value' => '$data->name'),
        array('header' => $model->attributeLabels()["user"], 'name' => 'user', 'value' => '$data->getUserRecorderName()'),
        array('header' => $model->attributeLabels()["last_updated"], 'name' => 'last_updated', 'value' => '$data->getLastUpdated()'),
        // array('header' => 'Date de l\'examen', 'name' => 'examDate', 'value' => '$data->getExamDate()'),
        array('header' => $model->attributeLabels()["examDate"], 'name' => 'examDate', 'value' => '$data->getAnswerByQuestionId("examdate")'),
//        array('header' => 'N° fiche', 'name' => 'id'),
//        array('header' => 'Nom du formulaire', 'value' => '$data->name'),
//        array('header' => 'Utilisateur', 'name' => 'user', 'value' => '$data->getUserRecorderName()'),
//        array('header' => 'Date de la saisie', 'name' => 'last_updated', 'value' => '$data->getLastUpdated()'),
//        // array('header' => 'Date de l\'examen', 'name' => 'examDate', 'value' => '$data->getExamDate()'),
//        array('header' => 'Date de l\'examen', 'name' => 'examDate', 'value' => '$data->getAnswerByQuestionId("examdate")'),
        array(
            'class' => 'CButtonColumn',
            'template' => '{view}',
            'buttons' => array(
                'view' => array(
                    'click' => 'function(){window.open(this.href,"_blank","left=100,top=100,width=1200,height=650,toolbar=yes, scrollbars=yes, resizable=yes, location=no");return false;}'
                ),
            ),
            'htmlOptions' => array('style' => 'width: 70px')
        ),
    ),
));
?>