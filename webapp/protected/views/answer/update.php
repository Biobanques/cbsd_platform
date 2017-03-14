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
            array('header' => Yii::t('common', 'birthDate'), 'value' => 'CommonTools::formatDateFR($data["birthDate"])')
        ),
    ));
}
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
        'action' => $this->createUrl('update', array('id' => $model->_id)),
        'enableAjaxValidation' => false,
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
    ));
    ?>
    
    <br>
    
    <div>
        <?php
        echo $model->renderTabbedGroup(Yii::app()->language, $model);
        ?>
    </div>

    <hr />

    <div>
        <?php
        echo CHtml::submitButton(Yii::t('common', 'saveBtn'), array('class' => 'btn btn-primary'));
        echo CHtml::link(Yii::t('common', 'cancel'), array('answer/affichepatient', 'id' => $model->_id), array('class' => 'btn btn-danger', 'style' => 'margin-top: -5px; margin-left:20px; padding-bottom:5px;'));
        if ($model->type == "genetique") {
            echo CHtml::ajaxSubmitButton(Yii::t('common', 'addGene'), $this->createUrl('updateandadd', array('id' => $model->_id)), array(
                'type' => 'POST',
                'success' => 'js:function(data){'
                . 'div_content = $(data).find("#questionnaire-form");'
                . '$("#questionnaire-form").html(div_content)'
                . '}',
                'error' => 'js:function(xhr, status, error){
                                alert(xhr.responseText);}',
                    ), array('class' => 'btn btn-primary', 'style' => 'margin-left:20px;')
            );
        }
        ?>
    </div>
    <?php
    $this->endWidget();
    ?>
</div>

