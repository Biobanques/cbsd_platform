<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'updateOnglet-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <div class="row">
        <p>Vous pouvez modifier un onglet en sélectionnant l'onglet à modifier puis de renseigner la valeur du nouvel onglet ainsi que son nouvel identifiant.</p>
        <?php
        echo CHtml::label('Onglet à modifier', 'old_onglet');
        echo CHtml::dropDownList('old_onglet', '', $questionGroup->getOnglets(), array("prompt" => "----", "required" => "required"));
        ?>
    </div>
    <div class="row">
    <?php
        echo CHtml::label('Nouvel onglet', '');
        echo CHtml::textfield('new_onglet', '', array("required" => "required"));
    ?>
    </div>
    <div class="row">
    <?php
        echo CHtml::label('Nouvel id', '');
        echo CHtml::textfield('new_id', '', array("required" => "required"));
    ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton('Enregistrer', array('class' => 'btn btn-default', 'style' => 'margin-top: 8px; padding-bottom: 23px;')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->