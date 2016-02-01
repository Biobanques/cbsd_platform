<div id="statusMsg">
    <?php if (!Yii::app()->user->hasFlash('success')): ?>
        <div class="flash-success">
            <?php echo Yii::app()->user->getFlash('success'); ?>
        </div>
    <?php endif; ?>

    <?php if (!Yii::app()->user->hasFlash('error')): ?>
        <div class="flash-error">
            <?php echo Yii::app()->user->getFlash('error'); ?>
        </div>
    <?php endif; ?>
</div>

<?php
$this->pageTitle = Yii::app()->name . ' - Affiche patient';
?>

<p><?php echo Yii::app()->user->name ?>, voici les fiches dont vous disposez pour ce patient.</p>
<div>
    <?php if (Yii::app()->user->getState('activeProfil') != "chercheur") { ?>
        <hr />
        <h4>Patient</h4>
        <?php
        $this->widget('bootstrap.widgets.TbGridView', array(
            'type' => 'striped bordered condensed',
            'dataProvider' => new CArrayDataProvider(array(get_object_vars($patient))),
            'template' => "{items}",
            'columns' => array(
                array('value' => '$data["id"]', 'name' => 'Patient Id', 'visible' => Yii::app()->user->isAdmin()),
                array('value' => '$data["birthName"]', 'header' => 'Nom de naissance'),
                array('value' => '$data["useName"]', 'header' => 'Nom d\'usage'),
                array('value' => '$data["firstName"]', 'header' => 'Prénom'),
                array('value' => '$data["birthDate"]', 'header' => 'Date de naissance'),
                array('value' => '$data["sex"]', 'header' => 'Genre'),
            ),
        ));
    }
    ?>

</div>
<hr />

<h4> Fiches patient renseignées : </h4>
<?php if (Yii::app()->user->isAuthorizedView(Yii::app()->user->getState('activeProfil'), "clinique")) { ?>
    <h5> Fiches cliniques </h5>
    <?php
    $this->widget('bootstrap.widgets.TbGridView', array(
        'type' => 'striped bordered condensed',
        'dataProvider' => $dataProviderCliniques,
        'template' => "{items}",
        'emptyText' => 'Vous n\'avez pas de fiches associées à ce patient.',
        'columns' => array(
            array('name' => 'name', 'header' => 'Identifiant de la fiche'),
            array('name' => 'Date de modification', 'value' => '$data->getLastUpdated()'),
            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'buttons' => array
                    (
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
    <h5> Fiches neuropathologiques </h5>
    <?php
    $this->widget('bootstrap.widgets.TbGridView', array(
        'type' => 'striped bordered condensed',
        'dataProvider' => $dataProviderNeuropathologiques,
        'template' => "{items}",
        'emptyText' => 'Vous n\'avez pas de fiches associées à ce patient.',
        'columns' => array(
            array('name' => 'name', 'header' => 'Identifiant de la fiche'),
            array('name' => 'Date de modification', 'value' => '$data->getLastUpdated()'),
            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'buttons' => array
                    (
                    'update' => array
                        (
                        'visible' => 'Yii::app()->user->id == $data->getUserId() && Yii::app()->user->isAuthorizedUpdate(Yii::app()->user->getState(\'activeProfil\'), "neuropathologique")'
                    ),
                    'delete' => array
                        (
                        'visible' => 'Yii::app()->user->id == $data->getUserId() && Yii::app()->user->isAuthorizedDelete(Yii::app()->user->getState(\'activeProfil\'), "neuropathologique")'
                    )
                ),
                'afterDelete' => 'function(link,success,data){ if(success) $("#statusMsg").html(data); }',
                'htmlOptions' => array('style' => 'width: 70px'),
            ),
        ),
    ));
}
?>
<?php if (Yii::app()->user->isAuthorizedView(Yii::app()->user->getState('activeProfil'), "genetique")) { ?>
    <hr />
    <h5> Fiches génétiques </h5>
    <?php
    $this->widget('bootstrap.widgets.TbGridView', array(
        'type' => 'striped bordered condensed',
        'dataProvider' => $dataProviderGenetiques,
        'template' => "{items}",
        'emptyText' => 'Vous n\'avez pas de fiches associées à ce patient.',
        'columns' => array(
            array('name' => 'name', 'header' => 'Identifiant de la fiche'),
            array('name' => 'Date de modification', 'value' => '$data->getLastUpdated()'),
            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'buttons' => array
                    (
                    'update' => array
                        (
                        'visible' => 'Yii::app()->user->id == $data->getUserId() && Yii::app()->user->isAuthorizedUpdate(Yii::app()->user->getState(\'activeProfil\'), "genetique")'
                    ),
                    'delete' => array
                        (
                        'visible' => 'Yii::app()->user->id == $data->getUserId() && Yii::app()->user->isAuthorizedDelete(Yii::app()->user->getState(\'activeProfil\'), "genetique")'
                    )
                ),
                'afterDelete' => 'function(link,success,data){ if(success) $("#statusMsg").html(data); }',
                'htmlOptions' => array('style' => 'width: 70px'),
            ),
        ),
    ));
}
?>

<?php
if (Yii::app()->user->getState('activeProfil') != "chercheur" && Yii::app()->user->getState('activeProfil') != "administrateur") {
    $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl('questionnaire/index'),
        'enableAjaxValidation' => false,
    ));
    ?>

    <div class="row">
        <div class="span3">
            <p>Saisir une nouvelle fiche : </p>
        </div>
        <div class="span3" style="margin:-5px;">
            <?php
            echo CHtml::dropDownList('form', '', Questionnaire::model()->getFiche(Yii::app()->user->getActiveProfil()), array('prompt' => '--- Choisir une fiche ---'));
            ?>
        </div>

        <div class="span3" style="margin:-5px;">
        <?php echo CHtml::submitButton('Saisir'); ?>
        </div>
    <?php $this->endWidget(); ?>
    </div>
    <?php
}?>