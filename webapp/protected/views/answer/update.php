<h4>Patient</h4>
<div class="well">
    <table cellpadding="20">
        <tr>
            <td><b>Nom de naissance : </b><?php echo $patient->birthName; ?></td>
            <td><b>Prénom : </b><?php echo $patient->firstName; ?></td>
        </tr>
        <tr>
            <td><b>Date de naissance : </b><?php echo $patient->birthDate; ?></td>
            <?php
            if (Yii::app()->user->profil == "administrateur")
                echo "<td><b>Patient ID : </b>" . $patient->id . "</td>";
            ?>
        </tr>
    </table>
</div>

<hr />

<h3 align="center">Formulaire <?php echo $model->name; ?></h3>
<p><b>Description: </b><?php echo $model->description; ?></p>

<hr />

<?php echo CHtml::errorSummary($model, null, null, array('class' => 'alert alert-error')); ?>
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'questionnaire-form',
        'action'=>$this->createUrl('update', array('id' => $model->_id)),
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
    <div style="display:inline; margin-left: 35%; width: 100px; ">
        <?php
        echo CHtml::submitButton('Enregistrer', array('class' => 'btn btn-primary', 'style' => 'margin-top:8px;padding-bottom:23px;'));
        echo CHtml::link('Annuler', array('answer/affichepatient', 'id' => $model->_id), array('class' => 'btn btn-primary', 'style' => 'margin-top: 2px; margin-left:20px;'));
        if ($model->type == "genetique") {
            echo CHtml::ajaxSubmitButton('Ajouter un gène', $this->createUrl('updateandadd', array('id' => $model->_id)), array(
                'type' => 'POST',
                'success' => 'js:function(data){'
                . 'div_content = $(data).find("#questionnaire-form");'
                . '$("#questionnaire-form").html(div_content)'
                . '}',
                'error' => 'js:function(xhr, status, error){
                                alert(xhr.responseText);}',
                    ), array('class' => 'btn btn-primary', 'style' => 'margin-top: 8px; margin-left:20px;padding-bottom:25px;')
            );
        }
        ?>
    </div>
    <?php
    $this->endWidget();
    ?>
</div>

