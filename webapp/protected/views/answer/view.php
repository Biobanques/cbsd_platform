<?php
Yii::app()->clientScript->registerScript('answer_view', "
$(document).ready(function() {
    var inputs = document.getElementsByTagName('input');
    var textareas = document.getElementsByTagName('textarea');
    var selectlist = document.getElementsByTagName('select');
    var len_inputs = inputs.length;
    var len_textareas = textareas.length;
    var len_selectlist = selectlist.length;

    for (var i = 0; i < len_inputs; i++) {
        inputs[i].disabled = true;
    }
    for (var i = 0; i < len_textareas; i++) {
        textareas[i].disabled = true;
    }
    for (var i = 0; i < len_selectlist; i++) {
        selectlist[i].disabled = true;
    }
});
");
?>

<h4>Patient</h4>
<div class="well">
    <table cellpadding="20">
        <tr>
            <td><b><?php echo Yii::t('common', 'birthName') ?> : </b><?php echo $patient->birthName; ?></td>
            <td><b><?php echo Yii::t('common', 'firstName') ?> : </b><?php echo $patient->firstName; ?></td>
        </tr>
        <tr>
            <td><b><?php echo Yii::t('common', 'birthDate') ?> : </b><?php echo $patient->birthDate; ?></td>
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

<?php
echo CHtml::link(Yii::t('common', 'htmlView'), array('answer/viewOnePage', 'id' => $model->_id));
?>
<div style="margin-top: -20px; text-align:right;">
    <?php
    $img = CHtml::image(Yii::app()->request->baseUrl . '/images/page_white_acrobat.png', 'export as pdf');
    echo CHtml::link(Yii::t('common', 'exportPdf') . $img, array('answer/exportPDF', 'id' => $model->_id), array());
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
<div style="display:inline; margin:40%; width: 100px; ">
    <?php
    echo CHtml::link(Yii::t('common', 'cancel'), array('answer/affichepatient', 'id' => $model->_id), array('class' => 'btn btn-default', 'style' => 'margin-top: -15px;margin-left:-40px;'));
    echo CHtml::link(Yii::t('common', 'updateAPatientForm'), array('answer/update', 'id' => $model->_id), array('class' => 'btn btn-default', 'style' => 'margin-top: -15px;margin-left:10px;'));
    ?>
</div>
<?php
$this->endWidget();
?>
