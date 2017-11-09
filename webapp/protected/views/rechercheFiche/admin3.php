<?php
Yii::app()->clientScript->registerScript('getUnchecked', "
       function getUncheckeds(){
            var unch = [];
            $('[name^=Answer_id_patient]').not(':checked,[name$=all]').each(function(){unch.push($(this).val());});
            return unch.toString();
       }
       "
);
?>

<div style="margin-left:20px;">
    <div class="myBreadcrumb">
        <div class="active"><?php echo Yii::t('common', 'queryAnonymous') ?></div>
        <div class="active"><?php echo Yii::t('common', 'queryFormulation') ?></div>
        <div class="active"><?php echo Yii::t('common', 'resultQuery') ?></div>
    </div>
</div>

<?php
if (Yii::app()->user->getActiveProfil() == "administrateur de projet") {
    ?> <h1><?php echo "Gestion de projet"; ?></h1>
<?php } else { ?>
    <h1><?php echo Yii::t('common', 'availablePatientForms') ?></h1>
<?php } ?>
<div><h4><u><?php echo Yii::t('common', 'queryAnonymous') ?></u></h4><?php if (isset($_SESSION['html'])) { echo $_SESSION['html']; }; ?>
<div id="queries" style="background-color:#E5F1F4;box-shadow: 5px 5px 5px #888888;padding:1px;"><?php if (isset($_SESSION['formulateQuery'])) { echo $_SESSION['formulateQuery']; }; ?><?php if (isset($_SESSION['htmlAvailable'])) { echo $_SESSION['htmlAvailable']; }; ?></div>
<?php echo CHtml::link(Yii::t('button', 'exportCSV'), array('rechercheFiche/exportCsv'), array('class' => 'btn btn-primary')); ?>
    
<?php echo CHtml::link('Nouvelle requête', array('rechercheFiche/admin'), array('class' => 'btn btn-danger')); ?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'action' => Yii::app()->createUrl('rechercheFiche/admin2'),
    'method' => 'post',
        ));

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'searchFiche-grid',
    'dataProvider' => $model->search(),
    'selectableRows' => 2,
    'beforeAjaxUpdate' => 'function(id,options){options.data={checkedIds:$.fn.yiiGridView.getChecked("searchFiche-grid","Answer_id_patient").toString(),
        uncheckedIds:getUncheckeds()};
        return true;}',
    'ajaxUpdate' => true,
    'enablePagination' => true,
    'columns' => array(
        array('id' => 'Answer_id_patient', 'class' => 'CCheckBoxColumn', 'checked' => isset($_GET['ajax']) ? 'Yii::app()->user->getState($data->_id)' : '0'),
        array('header' => $model->attributeLabels()["id_patient"], 'name' => 'id_patient'),
        array('header' => $model->attributeLabels()["type"], 'name' => 'type'),
        array('header' => $model->attributeLabels()["name"], 'name' => 'name'),
        array('header' => $model->attributeLabels()["user"], 'name' => 'user', 'value' => '$data->getUserRecorderName()'),
        array('header' => $model->attributeLabels()["last_updated"], 'name' => 'last_updated', 'value' => '$data->getLastUpdated()'),
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
    <div class="col-lg-6">
        <?php echo CHtml::submitButton(Yii::t('button', 'patientFormsAssociated'), array('name' => 'rechercher', 'class' => 'btn btn-primary')); ?>
    </div>
    <div class="col-lg-6">
        <?php echo CHtml::submitButton('Nouvelle requête sur toutes les fiches', array('name' => 'searchAll', 'class' => 'btn btn-primary')); ?>
    </div>
</div>

<?php $this->endWidget(); ?>