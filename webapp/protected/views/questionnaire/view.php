<h4>Patient</h4>
<div class="well">
    <table>
        <tr>
            <td><b>Nom : </b><?php echo $patient->useName; ?></td> 
            <td><b>Nom de naissance : </b><?php echo $patient->birthName; ?></td>
        </tr>
        <tr>
            <td><b>Prénom : </b><?php echo $patient->firstName; ?></td>
            <?php
            if (Yii::app()->user->profil == 1) {
                echo "<td><b>Patient ID : </b>" . $patient->id . "</td>";
            }
            ?>
        </tr>
    </table>  
</div>

<hr />

<h3 align="center">Formulaire <?php echo $model->name; ?></h3>
<p>Description: <?php echo $model->description; ?></p>

<hr />

<?php echo CHtml::link('Vue une page HTML', array('questionnaire/viewOnePage', 'id' => $model->_id)); ?>
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
<?php
echo CHtml::link('Vue une page HTML', array('questionnaire/viewOnePage', 'id' => $model->_id));
?>
<?php
$img = CHtml::image(Yii::app()->request->baseUrl . '/images/page_white_acrobat.png', 'export as pdf');
echo CHtml::link($img, array('questionnaire/exportPDF', 'id' => $model->_id), array());
?>

<?php echo CHtml::errorSummary($model, null, null, array('class' => 'alert alert-error')); ?>
<div>
    <?php
    echo $model->renderTabbedGroup(Yii::app()->language);
    ?>
</div>
<div style="display:inline; margin:40%; width: 100px; ">
    <?php
    echo CHtml::link('Annuler', array('answer/affichepatient', 'id' => $model->_id), array('class' => 'btn btn-primary', 'style' => 'margin-top: -15px;margin-left:20px;'));
    ?>
</div>