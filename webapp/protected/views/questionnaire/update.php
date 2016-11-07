<h4>Patient</h4>
<div class="well">
    <table>
        <tr>
            <td><b><?php echo Yii::t('common', 'birthName') ?> : </b><?php echo $patient->birthName; ?></td>
            <td><b><?php echo Yii::t('common', 'firstName') ?> : </b><?php echo $patient->firstName; ?></td>
        </tr>
        <tr>
            <td><b><?php echo Yii::t('common', 'birthDate') ?> : </b><?php echo $patient->birthDate; ?></td>
            <?php
            if (Yii::app()->user->profil == "administrateur") {
                echo "<td><b>Patient ID : </b>" . $patient->id . "</td>";
            }
            ?>
        </tr>
    </table>  
</div>
<?php $_SESSION["patientBirthDate"] = $patient->birthDate; ?>
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
    ));
    ?>
    <br>
    <div>
        <?php
        echo $model->renderTabbedGroup(Yii::app()->language, $model);
        ?>
    </div>
    <div style="display:inline; margin: 35%; width: 100px; ">
        <?php
        echo CHtml::submitButton(Yii::t('common', 'saveBtn'), array('class' => 'btn btn-default', 'style' => 'margin-top:8px;padding-bottom:23px;'));
        echo CHtml::link(Yii::t('common', 'cancel'), array('answer/affichepatient', 'id' => $model->_id), array('class' => 'btn btn-default', 'style' => 'margin-left:20px;'));
        ?>
    </div>
    <?php
    $this->endWidget();
    ?>

</div>