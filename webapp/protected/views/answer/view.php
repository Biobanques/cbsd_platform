<h4>Patient</h4>
<div class="well">
    <table cellpadding="20">
        <tr>
            <td><b>Nom : </b><?php echo $patient->useName; ?></td> 
            <td><b>Date de naissance : </b><?php echo $patient->birthDate; ?></td>
        </tr>
        <tr>
            <td><b>Prénom : </b><?php echo $patient->firstName; ?></td>
            <?php if (Yii::app()->user->profil == 1)
                echo "<td><b>Patient ID : </b>" . $patient->id . "</td>";
            ?>
        </tr>
    </table>  
</div>

<hr />

<h3 align="center">Formulaire <?php echo $model->id; ?></h3>
<p>Description: Formulaire <?php echo $model->id; ?> avec items 2015</p>

<hr />

<?php
echo CHtml::link('Vue une page HTML', array('questionnaire/viewOnePage', 'id' => $model->_id));
;
?>
<?php
$img = CHtml::image(Yii::app()->request->baseUrl . '/images/page_white_acrobat.png', 'export as pdf');
echo CHtml::link($img, array('questionnaire/exportPDF', 'id' => $model->_id), array());
?>
<div style="text-align:center;">
    <?php
    echo CHtml::link('Retour', array('answer/affichepatient', 'id' => $model->_id), array('class' => 'btn btn-default'));
    ?>
</div>

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
<?php
$this->endWidget();
?>
