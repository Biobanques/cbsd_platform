<div class="form" style="margin-left:30px;">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="note"><?php echo Yii::t("common", "ChampsObligatoires"); ?></p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <div class="col-lg-4">
            <?php echo $form->labelEx($model, 'prenom'); ?>
            <?php echo $form->textField($model, 'prenom', array('size' => 20, 'maxlength' => 250)); ?>
            <?php echo $form->error($model, 'prenom'); ?>
        </div>
        <div class="col-lg-4">
            <?php echo $form->labelEx($model, 'nom'); ?>
            <?php echo $form->textField($model, 'nom', array('size' => 20, 'maxlength' => 250)); ?>
            <?php echo $form->error($model, 'nom'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <?php echo $form->labelEx($model, 'login'); ?>
            <?php echo $form->textField($model, 'login', array('size' => 20, 'maxlength' => 250)); ?>
            <?php echo $form->error($model, 'login'); ?>
        </div>

        <div class="col-lg-4">
            <?php echo $form->labelEx($model, 'email'); ?>
            <?php echo $form->textField($model, 'email', array('size' => 20, 'maxlength' => 250)); ?>
            <?php echo $form->error($model, 'email'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <?php echo $form->labelEx($model, 'password'); ?>
            <?php echo $form->passwordField($model, 'password', array('size' => 20, 'maxlength' => 250)); ?>
            <?php echo $form->error($model, 'password'); ?>
        </div>
        <div class="col-lg-4">
            <?php echo $form->labelEx($model, 'repeatPassword'); ?>
            <?php echo $form->passwordField($model, 'repeatPassword', array('size' => 20, 'maxlength' => 250)); ?>
            <?php echo $form->error($model, 'repeatPassword'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <?php echo $form->labelEx($model, 'profil'); ?>
            <?php
            if (Yii::app()->user->isGuest)
                echo $form->checkBoxList($model, 'profil', User::model()->getArrayProfilFiltered(), array('onchange' => 'getProfil()', 'labelOptions' => array('style' => 'display:inline')));
            else
                echo $form->checkBoxList($model, 'profil', User::model()->getArrayProfilSorted(), array('onchange' => 'getProfil()', 'labelOptions' => array('style' => 'display:inline')));
            ?>
            <?php echo $form->error($model, 'profil'); ?>
        </div>
        <div class="col-lg-4">
            <?php echo $form->labelEx($model, 'telephone'); ?>
            <?php echo $form->textField($model, 'telephone', array('size' => 20, 'maxlength' => 250, 'placeholder' => 'Format 01 02 03 04 05')); ?>
            <?php echo $form->error($model, 'telephone'); ?>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-4">
            <?php echo $form->labelEx($model, 'gsm'); ?>
            <?php echo $form->textField($model, 'gsm', array('size' => 20, 'maxlength' => 250)); ?>
            <?php echo $form->error($model, 'gsm'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div id="address" style="display:none;">
                <?php echo CHtml::activeLabel($model, 'address', array('required' => true)); ?>
                <?php echo $form->textField($model, 'address', array('size' => 20, 'maxlength' => 250)); ?>
                <?php echo $form->error($model, 'address'); ?>
            </div>
        </div>
        <div class="col-lg-4">
            <div id="centre" style="display:none;">
                <?php echo CHtml::activeLabel($model, 'centre', array('required' => true)); ?>
                <?php echo $form->dropDownList($model, 'centre', User::model()->getArrayCentre(), array('prompt' => '----')); ?>
                <?php echo $form->error($model, 'centre'); ?>
            </div>
        </div>
    </div>    

    <div class="row buttons" style="float:left;">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Créer' : 'Enregistrer'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->

<script type="text/javascript">
    $(document).ready(function () {
        var clinicien = false;
        var neuropathologiste = false;
        var checkedVals = $(':checkbox:checked').map(function () {
            return this.value;
        }).get();
        for (var i = 0; i < checkedVals.length; i++) {
            if (checkedVals[i] == "clinicien")
                clinicien = true;
            if (checkedVals[i] == "neuropathologiste")
                neuropathologiste = true;
        }
        if (clinicien) {
            $('#address').show();
        }
        else {
            $('#User_address').val('');
            $('#address').hide();
        }
        if (neuropathologiste) {
            $('#centre').show();
        }
        else {
            $('#User_centre').val('');
            $('#centre').hide();
        }
        if (clinicien && neuropathologiste) {
            $('#address').show();
            $('#centre').show();
        }
        if (!clinicien && !neuropathologiste) {
            $('#User_address').val('');
            $('#User_centre').val('');
            $('#address').hide();
            $('#centre').hide();
        }
        //$('select option[value="inactif"]').attr("selected",true);
    });
    function getProfil() {
        var clinicien = false;
        var neuropathologiste = false;
        var checkedVals = $(':checkbox:checked').map(function () {
            return this.value;
        }).get();
        for (var i = 0; i < checkedVals.length; i++) {
            if (checkedVals[i] == "clinicien")
                clinicien = true;
            if (checkedVals[i] == "neuropathologiste")
                neuropathologiste = true;
        }
        if (clinicien) {
            $('#address').show();
        }
        else {
            $('#User_address').val('');
            $('#address').hide();
        }
        if (neuropathologiste) {
            $('#centre').show();
        }
        else {
            $('#User_centre').val('');
            $('#centre').hide();
        }
        if (clinicien && neuropathologiste) {
            $('#address').show();
            $('#centre').show();
        }
        if (!clinicien && !neuropathologiste) {
            $('#User_address').val('');
            $('#User_centre').val('');
            $('#address').hide();
            $('#centre').hide();
        }
    }

</script>