<?php

$this->breadcrumbs = array(
    'My documents' => array('index'),
    $model->id,
);
?>
<?php echo Yii::app()->user->name ?>
<hr />
<h4>Patient</h4>
<div class="well">
    <p><b>Nom : </b><?php echo $patient->useName; ?></p> 
    <p style="float:left; margin-left: 5px;"><b>Date de naissance : </b><?php echo $patient->birthDate; ?></p>
    <p style="clear:both;"><b>Prénom : </b><?php echo $patient->firstName; ?></p> 
    <p style="float:left; margin-left: 5px;"><b>Patient ID : </b><?php echo $patient->id; ?></p>
    
</div>
<hr />
<h3 align="center">Formulaire Parkinson v3.5</h3>
<p>Description: Formulaire Parkinson avec items 2015</p>
<hr />

<?php
echo CHtml::link('Vue une page HTML', array('questionnaire/viewOnePage', 'id' => $model->_id));
;
?>
<br><bR>
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'questionnaire-form',
        'enableAjaxValidation' => false,
    ));
    echo $model->renderTabbedGroup(Yii::app()->language);
    ?>
</div>
    <?php
    $this->endWidget();
    ?>

</div>
