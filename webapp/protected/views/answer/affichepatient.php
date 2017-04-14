<?php
Yii::app()->clientScript->registerScript('liste_fiche', "
$(function(){
    if (document.getElementById(\"form\").length -1 == 0) {
        $(\"#liste_fiche\").hide();
    }
});
");
?>

<div id="statusMsg">
    <?php
    if (!Yii::app()->user->hasFlash('success')) {
        echo Yii::app()->user->getFlash('success');
    }
    if (!Yii::app()->user->hasFlash('error')) {
        echo Yii::app()->user->getFlash('error');
    }
    ?>
</div>

<?php
$this->pageTitle = Yii::app()->name . ' - Affiche patient';
?>

<p><?php echo Yii::app()->user->name ?>, <?php echo Yii::t('common', 'viewPatientForms') ?></p>
<div>
    <?php if (Yii::app()->user->getState('activeProfil') != "Chercheur") { ?>
        <hr />
        <h4>Patient</h4>
        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => new CArrayDataProvider(array(get_object_vars($patient))),
            'template' => "{items}",
            'columns' => array(
                array('value' => '$data["id"]', 'name' => 'Patient Id', 'visible' => Yii::app()->user->isAdmin()),
                array('header' => Yii::t('common', 'birthName'), 'value' => '$data["birthName"]'),
                array('header' => Yii::t('common', 'firstName'), 'value' => '$data["firstName"]'),
                array('header' => Yii::t('common', 'birthDate'), 'value' => 'CommonTools::formatDateFR($data["birthDate"])'),
                array('header' => Yii::t('common', 'sex'), 'value' => '$data["sex"]'),
            ),
        ));
    }
    ?>

</div>
<hr />

<h4><?php echo Yii::t('common', 'patientFilled') ?> : </h4>
<?php if (Yii::app()->user->isAuthorizedView(Yii::app()->user->getState('activeProfil'), "clinique")) { ?>
    <h5><?php echo Yii::t('common', 'patientClinical') ?></h5>
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $dataProviderCliniques,
        'template' => "{items}",
        'emptyText' => Yii::t('common', 'noPatientForms'),
        'columns' => array(
            array('header' => Yii::t('common', 'formName'), 'name' => 'name'),
            array('header' => Yii::t('common', 'lastModified'), 'name' => 'Date de modification', 'value' => '$data->getLastUpdated()'),
            array(
                'class' => 'CButtonColumn',
                'buttons' => array
                    (
                    'view' => array
                        (
                        'visible' => 'Yii::app()->user->isAuthorizedViewFiche($data->getUserFicheById($data->_id), Yii::app()->user->getState(\'activeProfil\'), "clinique")'
                    ),
                    'update' => array
                        (
                        'visible' => 'Yii::app()->user->id == $data->getUserId() && Yii::app()->user->isAuthorizedUpdate(Yii::app()->user->getState(\'activeProfil\'), "clinique")'
                    ),
                    'delete' => array
                        (
                        'visible' => 'Yii::app()->user->id == $data->getUserId() && Yii::app()->user->isAuthorizedDelete(Yii::app()->user->getState(\'activeProfil\'), "clinique")'
                    )
                ),
                'afterDelete' => 'function(link,success,data){ if(success) $("#statusMsg").html(data); }',
                'htmlOptions' => array('style' => 'width: 70px'),
            ),
        ),
    ));
}
?>
<?php if (Yii::app()->user->isAuthorizedView(Yii::app()->user->getState('activeProfil'), "neuropathologique")) { ?>
    <hr />
    <h5><?php echo Yii::t('common', 'patientNeuropathologist') ?></h5>
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $dataProviderNeuropathologiques,
        'template' => "{items}",
        'emptyText' => Yii::t('common', 'noPatientForms'),
        'columns' => array(
            array('header' => Yii::t('common', 'formName'), 'name' => 'name'),
            array('header' => Yii::t('common', 'lastModified'), 'name' => 'Date de modification', 'value' => '$data->getLastUpdated()'),
            array(
                'class' => 'CButtonColumn',
                'buttons' => array
                    (
                    'view' => array
                        (
                        'visible' => 'Yii::app()->user->isAuthorizedViewFiche($data->getUserFicheById($data->_id), Yii::app()->user->getState(\'activeProfil\'), "clinique")'
                    ),
                    'update' => array
                        (
                        'visible' => 'Yii::app()->user->id == $data->getUserId() && Yii::app()->user->isAuthorizedUpdate(Yii::app()->user->getState(\'activeProfil\'), "neuropathologique")'
                    ),
                    'delete' => array
                        (
                        'visible' => 'Yii::app()->user->id == $data->getUserId() && Yii::app()->user->isAuthorizedDelete(Yii::app()->user->getState(\'activeProfil\'), "neuropathologique")'
                    )
                ),
                'afterDelete' => 'function(link,success,data){ if(success) $("#statusMsg").html(data); setTimeout(function(){ location.reload(); }, 2000); }',
                'htmlOptions' => array('style' => 'width: 70px'),
            ),
        ),
    ));
}
?>
<?php if (Yii::app()->user->isAuthorizedView(Yii::app()->user->getState('activeProfil'), "genetique")) { ?>
    <hr />
    <h5><?php echo Yii::t('common', 'patientGeneticist') ?></h5>
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => $dataProviderGenetiques,
        'template' => "{items}",
        'emptyText' => Yii::t('common', 'noPatientForms'),
        'columns' => array(
            array('header' => Yii::t('common', 'formName'), 'name' => 'name'),
            array('header' => Yii::t('common', 'lastModified'), 'name' => 'Date de modification', 'value' => '$data->getLastUpdated()'),
            array(
                'class' => 'CButtonColumn',
                'buttons' => array
                    (
                    'view' => array
                        (
                        'visible' => 'Yii::app()->user->isAuthorizedViewFiche($data->getUserFicheById($data->_id), Yii::app()->user->getState(\'activeProfil\'), "clinique")'
                    ),
                    'update' => array
                        (
                        'visible' => 'Yii::app()->user->id == $data->getUserId() && Yii::app()->user->isAuthorizedUpdate(Yii::app()->user->getState(\'activeProfil\'), "genetique")'
                    ),
                    'delete' => array
                        (
                        'visible' => 'Yii::app()->user->id == $data->getUserId() && Yii::app()->user->isAuthorizedDelete(Yii::app()->user->getState(\'activeProfil\'), "genetique")'
                    )
                ),
                'afterDelete' => 'function(link,success,data){ if(success) $("#statusMsg").html(data); setTimeout(function(){ location.reload(); }, 2000); }',
                'htmlOptions' => array('style' => 'width: 70px'),
            ),
        ),
    ));
}
?>

<?php
if (Yii::app()->user->getState('activeProfil') != "Chercheur" && Yii::app()->user->getState('activeProfil') != "Administrateur") {
    $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl('questionnaire/index'),
        'enableAjaxValidation' => false,
    ));
    ?>

    <div class="row" id="liste_fiche">
        <div class="col-lg-3">
            <p><?php echo Yii::t('common', 'insertPatientForm') ?> : </p>
        </div>
        <div class="col-lg-4">
            <?php
            echo CHtml::dropDownList('form', '', Questionnaire::model()->getFiche(Yii::app()->user->getActiveProfil(), $neuropath, $genetique), array('prompt' => '---' . Yii::t('common', 'choosePatientForm') . '---'));
            ?>
        </div>

        <div class="col-lg-5">
            <?php echo CHtml::submitButton(Yii::t('button', 'insert'), array('class' => 'btn btn-primary')); ?>
        </div>
        <?php $this->endWidget(); ?>
    </div>
<?php } ?>