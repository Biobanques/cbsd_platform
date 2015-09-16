<?php
$this->pageTitle = Yii::app()->name . ' - Affiche patient';
?>

<p><?php echo Yii::app()->user->name ?>, voici les fiches dont vous disposez pour ce patient.</p>
<hr />
<div>
    <h4>Patient</h4>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'dataProvider' => new CArrayDataProvider(array($model->getAttributes())),
    'template' => "{items}",
    'columns' => array(
        array('value' => '$data["id"]', 'name' => 'Patient Id'),
        array('value' => '$data["nom"]', 'header' => 'Nom'),
        array('value' => '$data["prenom"]', 'header' => 'Prénom'),
        array('value' => '$data["date_naissance"]', 'header' => 'Date de naissance'),
    ),
));
?>
    
</div>
<hr />

<h4> Fiches patient renseignés : </h4>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'dataProvider' => $dataProvider,
    'template' => "{items}",
    'emptyText' => 'Vous n\'avez pas de fiches associées à ce patient.',
    'columns' => array(
        array('name' => 'id', 'header' => 'Identifiant de la fiche'),
        array('name' => 'Date de modification', 'value' => '$data->getLastUpdated()'),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'htmlOptions' => array('style' => 'width: 50px'),
        ),
    ),
));
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
        <option selected="selected" disabled="disabled">Sélection du formulaire</option>
        <?php 
        foreach($questionnaire as $fiche=>$value){
            foreach($value as $k=>$v){
                if ($k=='id')
                echo "<option value=\"". $value['id'] . "\">" . $value['name_fr'] . "</option>";
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