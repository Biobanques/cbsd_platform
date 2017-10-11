<?php if (Yii::app()->user->getState('activeProfil') != "chercheur") { ?>
    <h4>Patient</h4>
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider' => new CArrayDataProvider(array(get_object_vars($patient))),
        'template' => "{items}",
        'columns' => array(
            array('value' => '$data["id"]', 'name' => 'Patient Id', 'visible' => Yii::app()->user->isAdmin()),
            array('header' => Yii::t('common', 'birthName'), 'value' => '$data["birthName"]'),
            array('header' => Yii::t('common', 'firstName'), 'value' => '$data["firstName"]'),
            array('header' => Yii::t('common', 'birthDate'), 'value' => '$data["birthDate"]')
        ),
    ));
}
$_SESSION["patientBirthDate"] = $patient->birthDate;
?>

<hr />

<h3 align="center">Formulaire <?php echo $model->name; ?></h3>
<p><b>Description: </b><?php echo $model->description; ?></p>

<hr />

<?php echo CHtml::errorSummary($model, null, null, array('class' => 'alert alert-danger')); ?>
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'questionnaire-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <br>

    <div>
        <?php echo $model->renderTabbedGroup(Yii::app()->language, $model); ?>
    </div>

    <hr />
    <div style="display:inline; margin: 35%; width: 100px; ">
        <?php
        echo CHtml::submitButton(Yii::t('button', 'saveBtn'), array('class' => 'btn btn-primary', 'style' => 'margin-top:8px'));
        echo CHtml::link(Yii::t('button', 'cancel'), array('answer/affichepatient', 'id' => $model->_id), array('class' => 'btn btn-danger', 'style' => 'margin-left:20px;'));
        ?>
    </div>

    <?php $this->endWidget(); ?>

</div>