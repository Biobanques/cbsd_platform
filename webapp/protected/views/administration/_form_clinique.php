<?php
$tabX = array("view" => "view", "update" => "update", "delete" => "delete", "create" => "create");
$profil = array("administrateur", "clinicien", "neuropathologiste", "geneticien", "chercheur");
?>
<div class="span3">

    <div class="form">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'droit-form',
            //'action' => Yii::app()->createUrl('/administration/index'),
            'enableAjaxValidation' => false,
        ));
        ?>
        <?php echo $form->errorSummary($model); ?>
        <p>Profil <?php echo $model->profil; ?> - Fiche <?php echo $model->type ;?></p>

        <?php
        echo $form->checkBoxList($model, 'role', $tabX, array('labelOptions' => array('style' => 'display:inline')));
        ?>
        <br>
        <div class="row buttons" style="float:left;">
            <?php echo CHtml::submitButton('Enregistrer'); ?>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>