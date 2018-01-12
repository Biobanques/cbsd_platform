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
<style>
    button.link {
        -moz-user-select: text;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1em;
        margin: 0;
        padding: 0;
        text-align: left;
    }

    button.link:hover span {
        text-decoration: underline;
    }
</style>
<div style="margin-left:20px;">
    <div class="myBreadcrumb">
        <div class="active"><?php echo CHtml::link(Yii::t('common', 'queryAnonymous'), array('rechercheFiche/admin'), array('style' => 'color:black')); ?></div>
        <div class="active"><?php echo CHtml::link(Yii::t('common', 'queryFormulation'), array('rechercheFiche/admin2'), array('style' => 'color:black')); ?></div>
        <div><?php echo Yii::t('common', 'resultQuery') ?></div>
    </div>
</div>

<?php
if (Yii::app()->user->getActiveProfil() == "administrateur de projet") {
    ?> <h1><?php echo "Gestion de projet"; ?></h1>
<?php } else { ?>
    <h1><?php echo Yii::t('common', 'availablePatientForms') ?></h1>
<?php } ?>
<div id="queries">
    <h4><u><?php echo Yii::t('common', 'history') ?></u></h4>

    <div class="row">
        <div class="col-lg-10">
            <?php if (isset($html->id_patient) && $html->id_patient != null) { ?>
                <ul><li>N° anonymat : 
                        <?php
                        foreach ($html->id_patient as $id_patient) {
                            echo $id_patient;
                            if ($id_patient != end($html->id_patient)) {
                                echo ", ";
                            }
                        }
                        ?>
                    </li></ul>
            <?php } ?>
            <?php if (isset($html->type) && $html->type != null) { ?>
                <ul><li>Type du formulaire : 
                        <?php
                        foreach ($html->type as $type) {
                            echo $type;
                            if ($type != end($html->type)) {
                                echo ", ";
                            }
                        }
                        ?>
                    </li></ul>
            <?php } ?>
            <?php
            if (isset($html->last_updated) && $html->last_updated != null) {
                echo "<ul><li>Période = " . $html->last_updated . "</li></ul>";
            }
            ?>
            <?php echo "<ul>" . $html->htmlQuestion . "</ul>" ?>
        </div>
        <div class="col-lg-2">
            <?php
            echo CHtml::link(
                    CHtml::image(Yii::app()->request->baseUrl . '/images/icons8-export-csv-50.png'), Yii::app()->createUrl("rechercheFiche/exportCsv")
                    , array('style' => "display:block;text-align:center")
            );
            ?><span><?php echo Yii::t('button', 'exportCSV') ?></span>
        </div>
    </div>

</div>

<br>

<?php echo CHtml::link('Nouvelle requête', array('rechercheFiche/admin'), array('class' => 'btn btn-danger')); ?>

<form action="/cbsd_platform/webapp/index.php?r=rechercheFiche/admin3" method="post"><input type="hidden" name="patientAll" value="patientAll"><button class="link" id="patientAll"><span>Toutes les fiches</span></button></form>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'action' => Yii::app()->createUrl('rechercheFiche/admin'),
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
if ($model->search()->getTotalItemCount() > 0) {
?>
<div class="row">
    <div class="col-lg-6">
        <?php echo CHtml::submitButton(Yii::t('button', 'patientFormsAssociated'), array('name' => 'rechercher', 'class' => 'btn btn-primary')); ?>
    </div>
    <div class="col-lg-6">
        <?php echo CHtml::submitButton(Yii::t('button', 'allPatientForms'), array('name' => 'searchAll', 'class' => 'btn btn-primary')); ?>
    </div>
</div>

<?php } $this->endWidget(); ?>