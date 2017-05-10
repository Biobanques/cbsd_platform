<div class="form" style="margin-left:30px;">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="note"><?php echo Yii::t("common", "requiredField"); ?></p>

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
            <?php echo $form->labelEx($model, 'email'); ?>
            <?php echo $form->textField($model, 'email', array('size' => 20, 'maxlength' => 250)); ?>
            <?php echo $form->error($model, 'email'); ?>
        </div>
        <div class="col-lg-4">
            <?php echo $form->labelEx($model, 'emailCompare'); ?>
            <?php echo $form->textField($model, 'emailCompare', array('size' => 20, 'maxlength' => 250)); ?>
            <?php echo $form->error($model, 'emailCompare'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <?php echo $form->labelEx($model, 'password'); ?>
            <?php echo $form->passwordField($model, 'password', array('size' => 20, 'maxlength' => 250)); ?>
            <?php echo $form->error($model, 'password'); ?>
        </div>
        <div class="col-lg-4">
            <?php echo $form->labelEx($model, 'passwordCompare'); ?>
            <?php echo $form->passwordField($model, 'passwordCompare', array('size' => 20, 'maxlength' => 250)); ?>
            <?php echo $form->error($model, 'passwordCompare'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <?php echo $form->labelEx($model, 'telephone'); ?>
            <?php echo $form->textField($model, 'telephone', array('size' => 20, 'maxlength' => 250, 'placeholder' => 'exemple format: 0145825443')); ?>
            <?php echo $form->error($model, 'telephone'); ?>
        </div>
        <div class="col-lg-4">
            <?php echo $form->labelEx($model, 'gsm'); ?>
            <?php echo $form->textField($model, 'gsm', array('size' => 20, 'maxlength' => 250)); ?>
            <?php echo $form->error($model, 'gsm'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div id="profil">
                <?php echo $form->labelEx($model, 'profil'); ?>
                <?php
                if (Yii::app()->user->isGuest) {
                    echo $form->checkBoxList($model, 'profil', User::model()->getArrayProfilFiltered(), array('onchange' => 'getProfil()', 'labelOptions' => array('style' => 'display:inline')));
                } else {
                    echo $form->checkBoxList($model, 'profil', User::model()->getArrayProfilSorted(), array('onchange' => 'getProfil()', 'labelOptions' => array('style' => 'display:inline')));
                }
                ?>
                <?php echo $form->error($model, 'profil'); ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div id="address" style="display:none;">
                <?php echo CHtml::activeLabel($model, 'address', array('required' => true)); ?>
                <?php echo $form->textField($model, 'address', array('size' => 20, 'maxlength' => 250)); ?>
                <?php echo $form->error($model, 'address'); ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div id="centre" style="display:none;">
                <?php echo CHtml::activeLabel($model, 'centre', array('required' => true)); ?>
                <?php echo $form->dropDownList($model, 'centre', User::model()->getArrayCentre(), array('prompt' => '----')); ?>
                <?php echo $form->error($model, 'centre'); ?>
            </div>
        </div>
    </div>

    <div class="row buttons">
        <div class="col-lg-12">
            <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('button', 'createBtn') : Yii::t('button', 'updateBtn'), array('class' => 'btn btn-primary')); ?>
            <?php echo CHtml::resetButton(Yii::t('button', 'reset'), array('class' => 'btn btn-danger')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->