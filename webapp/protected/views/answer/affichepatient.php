<?php
$this->pageTitle = Yii::app()->name . ' - Affiche patient';
$this->breadcrumbs = array(
    'Affichepatient',
);
?>

<p><?php echo Yii::app()->user->name ?>, voici les formulaires dont vous disposez pour ce patient.</p>
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
        //array('value' => '$data["nom_naissance"]', 'header' => 'nom de naissance'),
        array('value' => '$data["prenom"]', 'header' => 'Prénom'),
        array('value' => '$data["date_naissance"]', 'header' => 'Date de naissance'),
    ),
));
?>
    
</div>
<hr />

<h4> Formulaires patient renseignés : </h4>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'dataProvider' => $dataProvider,
    'template' => "{items}",
    'emptyText' => 'Vous n\'avez pas de formulaires associés à ce patient.',
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
    'action' => Yii::app()->createUrl('questionnaire/update'),
    'enableAjaxValidation' => false,
));
?>
<p> Saisir un nouveau formulaire : 
    <select>
        <option selected="selected" disabled="disabled">Sélection du formulaire</option>
        <option value="demence">Formulaire Démence</option>
        <option value="parkinson">Formulaire Parkinson</option>
    </select>
    <?php echo CHtml::submitButton('Saisir'); ?>
</p>
<?php $this->endWidget(); ?>