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
            <?php
            if (isset($html) && $html != null) {
                echo "<ul>" . $html->html . "</ul>";
            }
            ?>
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

<h4 align="center"> Résultats de la requête </h4>
<form action="/cbsd_platform/webapp/index.php?r=rechercheFiche/admin3" method="post"><input type="hidden" name="patientAll" value="patientAll"><button class="link" id="patientAll"><span><?php echo Yii::t('button', 'allForms') ?></span></button></form>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'action' => Yii::app()->createUrl('rechercheFiche/admin'),
    'method' => 'post',
        ));
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'searchFiche-grid',
    'dataProvider' => $model->search(),
    'columns' => array(
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
        <div class="col-lg-12">
            <?php echo CHtml::submitButton("Ajouter un autre formulaire (OU logique)", array('name' => 'searchAll', 'class' => 'btn btn-primary')); ?>
        </div>
    </div>

<?php } $this->endWidget(); ?>