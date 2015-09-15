<?php echo Yii::app()->user->name ?>

<hr />

<h4>Patient</h4>
<div class="well">
    <table cellpadding="20">
        <tr>
            <td><b>Nom : </b><?php echo $patient->useName; ?></td> 
            <td><b>Date de naissance : </b><?php echo $patient->birthDate; ?></td>
        </tr>
        <tr>
            <td><b>Prénom : </b><?php echo $patient->firstName; ?></td>
            <td><b>Patient ID : </b><?php echo $patient->id; ?></td>
        </tr>
    </table>  
</div>

<hr />

<h3 align="center">Formulaire <?php echo $model->id; ?> v3.5</h3>
<p>Description: Formulaire <?php echo $model->id; ?> avec items 2015</p>
<hr />

<br><bR>
<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'myModalContributors')); ?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Contributors</h4>
</div>

<div class="modal-body span5" >
    <?php echo $model->renderContributors(); ?>
</div>

<div class="modal-footer">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => 'Close',
        'url' => '#',
        'htmlOptions' => array('data-dismiss' => 'modal'),
    ));
    ?>
</div>

<?php $this->endWidget(); ?>

<?php
$this->widget('bootstrap.widgets.TbButton', array(
    'label' => 'Contributors',
    'type' => 'primary',
    'htmlOptions' => array(
        'data-toggle' => 'modal',
        'data-target' => '#myModalContributors',
    ),
));
?>
<?php echo CHtml::errorSummary($model, null, null, array('class' => 'alert alert-error')); ?>
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'questionnaire-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
    ));
    ?>
    <div style="text-align:center;">
        <?php
        echo "<div style=\"text-align:center\">" . CHtml::submitButton('Save', array('class' => 'btn btn-default', 'style' => 'margin:5px;')) . "</div>";
        echo CHtml::link('Retour', array('answer/affichepatient', 'id' => $model->_id), array('class' => 'btn btn-default'));
        ?>
    </div>
    <br>
    <div>
        <?php
        echo $model->renderTabbedGroup(Yii::app()->language);
        ?>
    </div>
    <?php
    $this->endWidget();
    ?>

</div>
