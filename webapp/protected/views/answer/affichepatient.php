<?php
$this->pageTitle = Yii::app()->name . ' - Affiche patient';
?>

<p><?php echo Yii::app()->user->name ?>, voici les fiches dont vous disposez pour ce patient.</p>
<hr />
<div>
    <?php if (Yii::app()->user->getState('activeProfil') != "chercheur") { ?>
        <h4>Patient</h4>
        <?php
        $this->widget('bootstrap.widgets.TbGridView', array(
            'type' => 'striped bordered condensed',
            'dataProvider' => new CArrayDataProvider(array(get_object_vars($patient))),
//    'dataProvider' => new CArrayDataProvider(array($model->getAttributes())),
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

<h4> Fiches patient renseignés : </h4>

<?php
if (Yii::app()->user->getActiveProfil() == "clinicien") {
    $this->widget('bootstrap.widgets.TbGridView', array(
        'type' => 'striped bordered condensed',
        'dataProvider' => $dataProvider,
        'template' => "{items}",
        'emptyText' => 'Vous n\'avez pas de fiches associées à ce patient.',
        'columns' => array(
            array('name' => 'name', 'header' => 'Identifiant de la fiche'),
            array('name' => 'Date de modification', 'value' => '$data->getLastUpdated()'),
            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'htmlOptions' => array('style' => 'width: 70px'),
            ),
        ),
    ));
}
?>

<?php
if (Yii::app()->user->getActiveProfil() == "geneticien") {
    $this->widget('bootstrap.widgets.TbGridView', array(
        'type' => 'striped bordered condensed',
        'dataProvider' => $dataProvider,
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
                        'visible' => '(Yii::app()->user->id == $data->getUserId())?true:false;'
                    ),
                    'delete' => array
                        (
                        'visible' => '(Yii::app()->user->id == $data->getUserId())?true:false;'
                    )
                ),
                'htmlOptions' => array('style' => 'width: 70px'),
            ),
        ),
    ));
}
?>

<?php
if (Yii::app()->user->getActiveProfil() == "neuropathologiste") {
    $this->widget('bootstrap.widgets.TbGridView', array(
        'type' => 'striped bordered condensed',
        'dataProvider' => $dataProvider,
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
                        'visible' => '(Yii::app()->user->id == $data->getUserId())?true:false;'
                    ),
                    'delete' => array
                        (
                        'visible' => '(Yii::app()->user->id == $data->getUserId())?true:false;'
                    )
                ),
                'htmlOptions' => array('style' => 'width: 70px'),
            ),
        ),
    ));
}
?>

<?php
if (Yii::app()->user->getActiveProfil() == "chercheur") {
    $this->widget('bootstrap.widgets.TbGridView', array(
        'type' => 'striped bordered condensed',
        'dataProvider' => $dataProvider,
        'template' => "{items}",
        'emptyText' => 'Vous n\'avez pas de fiches associées à ce patient.',
        'columns' => array(
            array('name' => 'name', 'header' => 'Identifiant de la fiche'),
            array('name' => 'Date de modification', 'value' => '$data->getLastUpdated()'),
            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'template' => '{view}',
                'htmlOptions' => array('style' => 'width: 70px'),
            ),
        ),
    ));
}
?>

<?php
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
        <select name="form">
            <option selected="selected" disabled="disabled">--- Sélectionner une fiche ---</option>
            <?php
            foreach ($questionnaire as $fiche => $value) {
                foreach ($value as $k => $v) {
                    if ($k == 'id') {
                        if (Yii::app()->user->getActiveProfil() == "clinicien" && $value['type'] == 'clinique')
                            echo "<option value=\"" . $value['id'] . "\">" . $value['name'] . "</option>";
                        else if (Yii::app()->user->getActiveProfil() == "geneticien" && $value['type'] == 'genetique')
                            echo "<option value=\"" . $value['id'] . "\">" . $value['name'] . "</option>";
                        else if (Yii::app()->user->getActiveProfil() == "neuropathologiste" && $value['type'] == 'neuropathologique')
                            echo "<option value=\"" . $value['id'] . "\">" . $value['name'] . "</option>";
                    }
                }
            }
            ?>
        </select>
    </div>
    <div class="span3" style="margin:-5px;">
        <?php echo CHtml::submitButton('Saisir'); ?>
    </div>
    <?php $this->endWidget(); ?>
</div>