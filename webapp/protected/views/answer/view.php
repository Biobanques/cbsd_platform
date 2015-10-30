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
<p>Description: <?php echo $model->description; ?></p>

<hr />

<?php
echo CHtml::link('Vue une page HTML', array('questionnaire/viewOnePage', 'id' => $model->_id));
?>
<?php
$img = CHtml::image(Yii::app()->request->baseUrl . '/images/page_white_acrobat.png', 'export as pdf');
echo CHtml::link($img, array('answer/exportPDF', 'id' => $model->_id), array());
?>

<br /><br />

<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'questionnaire-form',
        'enableAjaxValidation' => false,
    ));
    echo $model->renderTabbedGroup(Yii::app()->language);
    ?>
</div>
<div style="display:inline; margin:40%; width: 100px; ">
    <?php
    echo CHtml::link('Annuler', array('answer/affichepatient', 'id' => $model->_id), array('class' => 'btn btn-primary', 'style' => 'margin-top: -15px;margin-left:20px;'));
    ?>
</div>
<?php
$this->endWidget();
?>
