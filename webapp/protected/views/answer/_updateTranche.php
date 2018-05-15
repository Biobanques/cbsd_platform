<div class="wide form">

    <p class="note"><?php echo Yii::t('common', 'requiredField'); ?></p>

    <?php echo $form->errorSummary($modelTranche, null, null, array('class' => 'alert alert-danger')); ?>

    <div class="row">
        <div class="col-lg-12">
            <?php echo $form->labelEx($modelTranche, 'id_donor'); ?>
            <?php echo $form->textField($modelTranche, 'id_donor', array('value' => Neuropath::model()->findByAttributes(array('id_cbsd' => $patient->id))->id_donor, 'readonly' => 'true', 'size' => 20, 'maxlength' => 250)); ?>
            <?php echo $form->error($modelTranche, 'id_donor'); ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <?php echo $form->labelEx($modelTranche, 'presenceCession'); ?>
            <?php echo $form->textField($modelTranche, 'presenceCession', array('size' => 20, 'maxlength' => 250)); ?>
            <?php echo $form->error($modelTranche, 'presenceCession'); ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <?php echo $form->labelEx($modelTranche, 'hemisphere'); ?>
            <?php echo $form->textField($modelTranche, 'hemisphere', array('size' => 20, 'maxlength' => 250)); ?>
            <?php echo $form->error($modelTranche, 'hemisphere'); ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <?php echo $form->labelEx($modelTranche, 'idPrelevement'); ?>
            <?php echo $form->textField($modelTranche, 'idPrelevement', array('value' => Neuropath::model()->findByAttributes(array('id_cbsd' => $patient->id))->id_prelevement, 'readonly' => 'true', 'size' => 20, 'maxlength' => 250)); ?>
            <?php echo $form->error($modelTranche, 'idPrelevement'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?php echo $form->labelEx($modelTranche, 'nameSamplesTissue'); ?>
            <?php echo $form->textField($modelTranche, 'nameSamplesTissue', array('size' => 20, 'maxlength' => 250)); ?>
            <?php echo $form->error($modelTranche, 'nameSamplesTissue'); ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <?php echo $form->labelEx($modelTranche, 'originSamplesTissue'); ?>
            <?php echo $form->textField($modelTranche, 'originSamplesTissue', array('size' => 20, 'maxlength' => 250)); ?>
            <?php echo $form->error($modelTranche, 'originSamplesTissue'); ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <?php echo $form->labelEx($modelTranche, 'prelevee'); ?>
            <?php echo $form->textField($modelTranche, 'prelevee', array('size' => 20, 'maxlength' => 250)); ?>
            <?php echo $form->error($modelTranche, 'prelevee'); ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <?php echo $form->labelEx($modelTranche, 'nAnonymat'); ?>
            <?php echo $form->textField($modelTranche, 'nAnonymat', array('size' => 20, 'maxlength' => 250)); ?>
            <?php echo $form->error($modelTranche, 'nAnonymat'); ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <?php echo $form->labelEx($modelTranche, 'qualite'); ?>
            <?php echo $form->textField($modelTranche, 'qualite', array('size' => 20, 'maxlength' => 250)); ?>
            <?php echo $form->error($modelTranche, 'qualite'); ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <?php echo $form->labelEx($modelTranche, 'quantityAvailable'); ?>
            <?php echo $form->dropDownList($modelTranche, 'quantityAvailable', Tranche::model()->setQuantityAvailable(), array('prompt' => '---')); ?>
            <?php echo $form->error($modelTranche, 'quantityAvailable'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?php echo $form->labelEx($modelTranche, 'storageConditions'); ?>
            <?php echo $form->textField($modelTranche, 'storageConditions', array('size' => 20, 'maxlength' => 250)); ?>
            <?php echo $form->error($modelTranche, 'storageConditions'); ?>
        </div>
    </div>

</div><!-- form -->