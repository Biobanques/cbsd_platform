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
        'enableAjaxValidation' => false,
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
    ));
    ?>
    <br>
    <div>
        <?php
        echo $model->renderTabbedGroup(Yii::app()->language);
        ?>
    </div>
    <div style="display:inline; margin-left: 35%; width: 100px; ">
        <?php
        echo CHtml::submitButton('Enregistrer', array('class' => 'btn btn-primary', 'style' => 'margin-top:8px;padding-bottom:23px;'));
        echo CHtml::link('Annuler', array('answer/affichepatient', 'id' => $model->_id), array('class' => 'btn btn-primary', 'style' => 'margin-top: 3px; margin-left:20px;'));
        ?>
    </div>
    <?php
    $this->endWidget();
    ?>

</div>
